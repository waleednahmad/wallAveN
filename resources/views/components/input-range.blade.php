@props(['min', 'max', 'id' => ''])
<div x-data="{
    progress: 0,
    v: 0,
    min: {{ $min }},
    max: {{ $max }},
    mouseDown: false,
    init() {
        this.v = this.$refs.input.value;
        wrapper = this.$refs.wrapper;
        this.progress = (this.v / this.max) * 100;
        this.$refs.input.addEventListener('input', () => {
            this.v = this.$refs.input.value;
        });

        setTimeout(() => this.fireInputEvent(), 500)

        this.$watch('v', (value) => {
            this.progress = (value / this.max) * 100;
        })

        wrapper.addEventListener('mousedown', (e) => {
            this.mouseDown = true;
            this.calculateMousePositionValue(e);
        });

        document.addEventListener('mouseup', () => {
            this.mouseDown = false;
        });

        document.addEventListener('mousemove', (e) => {
            if (this.mouseDown) {
                this.calculateMousePositionValue(e);
            }
        });
    },
    calculateMousePositionValue: function(e) {
        wrapper = this.$refs.wrapper;

        if (e.clientX < this.$refs.wrapper.offsetLeft) {
            this.$refs.input.value = this.min;
        } else if (e.clientX > this.$refs.wrapper.offsetLeft + this.$refs.wrapper.offsetWidth) {
            this.$refs.input.value = this.max;
        } else {
            let offsetFromWrapper = e.clientX - this.$refs.wrapper.offsetLeft;
            this.$refs.input.value = Math.max(Math.round((offsetFromWrapper / wrapper.offsetWidth) * this.max), this.min);
        }

        this.fireInputEvent();
    },
    fireInputEvent: function() {
        this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
    }
}">
    {{-- Old input --}}
    {{-- We don't use its style now, But it should be there for native input elements events. --}}
    <input {{ $attributes }} type="range" min="{{ $min }}" max="{{ $max }}" x-ref="input"
        class="!hidden" :title="v">

    <div>
        <div x-ref="wrapper" style="position: relative; user-select: none; cursor: pointer;">
            <div style="height: 8px; width: 100%; border-radius: 9999px; overflow: hidden; background-color: #172C42;">
                <div style="background-color: #1D4ED8; height: 100%; position: relative;" :style="{ 'width': progress + '%' }">
                </div>
            </div>
            <div style="position: absolute; top: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; background-color: #1D4ED8; color: white; padding-left: 4px; padding-right: 4px; min-width: 26px; height: 26px; border: 1px solid white; border-radius: 4px;"
                :style="{ 'left': progress + '%' }">
                @isset($value)
                    {{ $value }}
                @else
                    <span x-text="v" style="font-size: 0.875rem; font-weight: 500;"></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
