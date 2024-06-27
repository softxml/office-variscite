jQuery(function($){

    $("#dl-form").submit(function(e) {
        e.preventDefault();


        $("#form-message").show();
        $("#submit-message").hide();
        if ($('#FNAME').val().length < 2 ) {
            $("#form-message").text( "Please enter first name" );
            return;
        }
        if ($('#LNAME').val().length < 2 ) {
            $("#form-message").text( "Please enter last name" );
            return;
        }
        if ($('#COUNTRY').val() == "") {
            $("#form-message").text( "Please select a country" );
            return;
        }
        if ($('#MMERGE6').val().length < 2 ) {
            $("#form-message").text( "Please enter a company" );
            return;
        }
        if ($('#EMAIL').val().length < 2 ) {
            $("#form-message").text( "Please enter a valid email address" );
            return;
        }

        // Passed all validation
        $("#submit-message").show();
        $("#submit-message").text("Working on it... hold on...");

        var form = $(this);
        var actionUrl = form.attr('action');

        $.ajax({
            type: "POST",
            url: '/wp-admin/admin-ajax.php?action=asym_mc',
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                $("#form-message").text("");
                $("#form-message").hide();
                $("#submit-message").show();
                $("#submit-message").text("Thank you, your sign-up request was successful!");
                $("#dl-form input").val("");
                $("#dl-form select").val("");
                $("#dl-form").hide();
                $(".form-box h1").hide();
                $(".form-box h2").hide();

                console.log('success');
                const downloadPdf = document.getElementById('dl-btn');
                downloadPdf.click();
            },
            error: function(xhr) {
                console.log("An error occured: " + xhr.status + " " + xhr.statusText);

                $("#form-message").text("");
                $("#form-message").hide();
                $("#submit-message").show();
                $("#submit-message").text("We're sorry, something went wrong, please try again later.");

            }
        });
        // return true; // Can submit the form data to the server
    });

});
