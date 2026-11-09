window.resizeImageForUpload = async function (file, maxWidth = 2000) {
    if (!file.type.startsWith('image/') || file.type === 'image/svg+xml' || file.type === 'image/gif') return file;
    try {
        const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
        if (bitmap.width <= maxWidth && file.size < 5 * 1024 * 1024) { bitmap.close(); return file; }
        const scale = Math.min(1, maxWidth / bitmap.width);
        const canvas = document.createElement('canvas');
        canvas.width = Math.round(bitmap.width * scale);
        canvas.height = Math.round(bitmap.height * scale);
        canvas.getContext('2d').drawImage(bitmap, 0, 0, canvas.width, canvas.height);
        bitmap.close();
        const type = file.type === 'image/png' ? 'image/png' : 'image/jpeg';
        const blob = await new Promise(function (r) { canvas.toBlob(r, type, 0.92); });
        if (!blob) return file;
        const name = file.name.replace(/\.[^.]+$/, '') + (type === 'image/png' ? '.png' : '.jpg');
        return new File([blob], name, { type: type, lastModified: Date.now() });
    } catch (e) {
        return file;
    }
};

window.uploadResized = async function (event, wire) {
    const input = event.target;
    const original = Array.from(input.files || []);
    if (!original.length) return;
    const label = document.querySelector('label[for="' + input.id + '"]');
    const oldText = label ? label.textContent : '';
    const setLabel = function (t) { if (label) label.textContent = t; };
    input.disabled = true;
    try {
        const files = [];
        for (let i = 0; i < original.length; i++) {
            setLabel('Preparing images... ' + (i + 1) + '/' + original.length);
            files.push(await window.resizeImageForUpload(original[i]));
        }
        setLabel('Uploading...');
        await new Promise(function (resolve, reject) {
            wire.uploadMultiple('uploadedImages', files, resolve, reject, function (e) {
                if (e && e.detail && e.detail.progress !== undefined) setLabel('Uploading... ' + e.detail.progress + '%');
            });
        });
    } catch (e) {
        alert('Image upload failed. Please try again.');
    } finally {
        input.disabled = false;
        input.value = '';
        setLabel(oldText);
    }
};
