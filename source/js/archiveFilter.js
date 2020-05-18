export default () => {
    const formElements = document.querySelectorAll('form.js-submit');
    Object.keys(formElements).map(key => formElements[key]).forEach(formElement => {
        const submitHandler = (e) => {
            formElement.submit();
        };
        
        const selectElements = formElement.querySelectorAll('select');
        if (Object.keys(selectElements).length <= 0) {
            return;
        }

        Object.keys(selectElements).map(key => selectElements[key]).forEach(selectElement => {
            selectElement.addEventListener('change', submitHandler);
        });

    });
};
  