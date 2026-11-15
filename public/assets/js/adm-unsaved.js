/*
 * Unsaved-changes tracking for admin forms (Livewire + Alpine).
 *
 *   <form wire:submit="save" x-data="admUnsaved({ calls: ['toggleCategory'], ignore: ['searchCategory'] })">
 *     ... <div x-show="dirty">You have unsaved changes</div>
 *
 * `dirty` turns true when the user types or picks something inside the form
 * (fields marked data-unsaved-ignore, like list searches, don't count),
 * when one of the listed Livewire methods runs, or when a property other than
 * the ignored ones is sent to the server (CKEditor, uploads).
 * While dirty, leaving the page asks for confirmation. Saving clears the warning
 * for the redirect that follows a successful save.
 */
window.admUnsaved = function (opts) {
    opts = opts || {};
    var calls = opts.calls || [];
    var ignore = opts.ignore || [];

    return {
        dirty: false,
        saving: false,

        init: function () {
            var self = this;
            var form = this.$el;

            var mark = function (e) {
                var t = e && e.target;
                if (!t || !t.closest || t.closest('[data-unsaved-ignore]')) return;
                if (t.type === 'file') return; // counted when the upload reaches the component
                self.dirty = true;
            };
            form.addEventListener('input', mark);
            form.addEventListener('change', mark);
            // select2 / jQuery plugins trigger a jQuery-only change event
            if (window.jQuery) window.jQuery(form).on('change', 'select', mark);

            var id = this.$wire && this.$wire.$id;
            if (window.Livewire && id) {
                window.Livewire.hook('commit', function (payload) {
                    if (payload.component.id !== id) return;
                    var commit = payload.commit || {};
                    var names = (commit.calls || []).map(function (c) { return c.method; });

                    if (names.indexOf('save') !== -1) {
                        self.saving = true;
                        payload.succeed(function () {
                            // a successful save redirects; if it stays (validation errors), warn again later
                            setTimeout(function () { self.saving = false; }, 2000);
                        });
                        payload.fail(function () { self.saving = false; });
                        return;
                    }

                    var updated = Object.keys(commit.updates || {}).filter(function (k) {
                        return !ignore.some(function (i) { return k === i || k.indexOf(i + '.') === 0; });
                    });
                    if (updated.length || names.some(function (m) { return calls.indexOf(m) !== -1; })) {
                        self.dirty = true;
                    }
                });
            }

            window.addEventListener('beforeunload', function (e) {
                if (self.dirty && !self.saving) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        },
    };
};
