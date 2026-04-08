(function() {
    const initNotes = () => {
        const instances = document.querySelectorAll('.w-notes');
        instances.forEach(container => {
            const textarea = container.querySelector('.w-notes__textarea');
            if (!textarea) return;

            const savedNote = localStorage.getItem('modulor_note_content');
            if (savedNote) textarea.value = savedNote;

            textarea.addEventListener('input', (e) => {
                localStorage.setItem('modulor_note_content', e.target.value);
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNotes);
    } else {
        initNotes();
    }
})();