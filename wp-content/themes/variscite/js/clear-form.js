jQuery(document).ready(function() {

    var form_elements = [
        document.querySelector("#quoteFormWidget #first_name"),
        document.querySelector("#quoteFormWidget #last_name"),
        document.querySelector("#quoteFormWidget #email"),
        document.querySelector("#quoteFormWidget #company"),
        document.querySelector("#quoteFormWidget #country"),
        document.querySelector("#quoteFormWidget #phone"),
        document.querySelector("#quoteFormWidget #note"),
    ];

    setTimeout(function() {
        form_elements.forEach(element => {
            if (element.value !== "" || element.value) {
                document.querySelector("#"+element.id).closest(".field-"+element.id).classList.add("active");
            }
        });
    }, 100);

});