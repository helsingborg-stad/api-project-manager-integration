export default () => {
    Object.values(document.querySelectorAll('form.js-submit')).forEach(formElement => {
        const submitHandler = (e) => {
            formElement.submit();
        };
        
        const selectElements = formElement.querySelectorAll('select');
        
        if (selectElements.length <= 0) {
            return;
        }
        selectElements.forEach(selectElement => {
            selectElement.addEventListener('change', submitHandler);
        });

    });
};
  