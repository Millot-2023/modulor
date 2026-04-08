// blocks/w-notes/w-notes.js

(function() {
    const initNotes = () => {
        // On cherche toutes les instances du bloc
        const instances = document.querySelectorAll('.w-notes');

        instances.forEach(container => {
            const textarea = container.querySelector('.w-notes__textarea');
            if (!textarea) return;

            // Chargement local (clé unique par instance si besoin, ici générique)
            const savedNote = localStorage.getItem('modulor_note_content');
            if (savedNote) {
                textarea.value = savedNote;
            }

            // Sauvegarde automatique
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