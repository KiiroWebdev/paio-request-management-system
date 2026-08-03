document.addEventListener('DOMContentLoaded', () => {

    const labelInput = document.querySelector('input[name="label"]');
    const fieldNameInput = document.querySelector('input[name="field_name"]');

    if (!labelInput || !fieldNameInput) {
        return;
    }

    let manuallyEdited = false;

    fieldNameInput.addEventListener('input', () => {
        manuallyEdited = true;
    });

    labelInput.addEventListener('input', () => {

        if (manuallyEdited) {
            return;
        }

        fieldNameInput.value = labelInput.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');

    });

});