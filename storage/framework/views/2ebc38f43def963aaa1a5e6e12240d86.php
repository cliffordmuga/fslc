
<?php
    $flashMessages = collect();
    $id = 0;

    $push = function (string $type, mixed $text) use (&$flashMessages, &$id) {
        if (!filled($text)) return;
        $flashMessages->push([
            'id'   => 'flash-' . (++$id) . '-' . uniqid(),
            'type' => $type,
            'text' => (string) $text,
        ]);
    };

    $push('success', session('success'));
    $push('error',   session('error'));
    $push('warning', session('warning'));
    $push('info',    session('info'));

    // "status" is used by Laravel's built-in auth — only show if not a duplicate of success
    if (filled(session('status')) && session('status') !== session('success')) {
        $push('info', session('status'));
    }

    $importErrors = session('import_errors', []);
    if (is_array($importErrors) && count($importErrors) > 0) {
        $n = count($importErrors);
        $push('warning', $n === 1
            ? '1 row had issues during import.'
            : "{$n} rows had issues during import.");
    }

    $flashMessages = $flashMessages->values()->all();
?>


<script>
    window.__flashMessages = <?php echo json_encode($flashMessages, JSON_HEX_QUOT | JSON_HEX_TAG, 512) ?>;
</script>

<div
    x-data="{
        messages: (window.__flashMessages ?? []),

        typeMap: {
            success: {
                wrap:  'border-l-4 border-green-500 bg-green-50',
                icon:  'text-green-600',
                text:  'text-green-900',
                close: 'text-green-600 hover:text-green-800 hover:bg-green-100',
                bar:   'bg-green-500',
                path:  'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
            },
            error: {
                wrap:  'border-l-4 border-red-500 bg-red-50',
                icon:  'text-red-600',
                text:  'text-red-900',
                close: 'text-red-600 hover:text-red-800 hover:bg-red-100',
                bar:   'bg-red-500',
                path:  'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
            },
            warning: {
                wrap:  'border-l-4 border-yellow-500 bg-yellow-50',
                icon:  'text-yellow-600',
                text:  'text-yellow-900',
                close: 'text-yellow-600 hover:text-yellow-800 hover:bg-yellow-100',
                bar:   'bg-yellow-500',
                path:  'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
            },
            info: {
                wrap:  'border-l-4 border-blue-500 bg-blue-50',
                icon:  'text-blue-600',
                text:  'text-blue-900',
                close: 'text-blue-600 hover:text-blue-800 hover:bg-blue-100',
                bar:   'bg-blue-500',
                path:  'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z',
            },
        },

        get(type, key) {
            return (this.typeMap[type] ?? this.typeMap.info)[key];
        },

        dismiss(index) {
            this.messages.splice(index, 1);
        },

        autoDismiss() {
            const tick = () => {
                if (!this.messages.length) return;
                setTimeout(() => { this.messages.shift(); tick(); }, 5000);
            };
            tick();
        }
    }"
    x-init="autoDismiss()"
    x-show="messages.length > 0"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="fixed top-[4.5rem] right-4 z-[110] space-y-2 w-[min(100vw-2rem,22rem)] pointer-events-auto"
    role="status"
    aria-live="polite"
    aria-atomic="false">

    <template x-for="(msg, index) in messages" :key="msg.id">
        <div
            :class="get(msg.type, 'wrap')"
            class="relative overflow-hidden shadow-medium"
            role="alert"
            :aria-label="msg.type.charAt(0).toUpperCase() + msg.type.slice(1) + ': ' + msg.text">

            <div class="flex items-start gap-3 px-4 py-3">
                
                <svg :class="get(msg.type, 'icon')" class="w-5 h-5 flex-shrink-0 mt-0.5"
                     fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" :d="get(msg.type, 'path')" clip-rule="evenodd"/>
                </svg>

                
                <p :class="get(msg.type, 'text')"
                   class="flex-1 text-sm font-medium leading-snug break-words"
                   x-text="msg.text"></p>

                
                <button
                    @click="dismiss(index)"
                    type="button"
                    :class="get(msg.type, 'close')"
                    class="flex-shrink-0 p-1 -mr-1 transition-colors"
                    aria-label="Dismiss notification">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            
            <div x-show="index === 0" class="absolute bottom-0 left-0 right-0 h-0.5" aria-hidden="true">
                <div :class="get(msg.type, 'bar')" class="h-full animate-flash-progress"></div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes flash-progress {
        from { width: 100%; }
        to   { width: 0%;   }
    }
    .animate-flash-progress {
        animation: flash-progress 5s linear forwards;
    }
</style>
<?php /**PATH C:\laragon\www\fslc\resources\views/components/cms/flash-messages.blade.php ENDPATH**/ ?>