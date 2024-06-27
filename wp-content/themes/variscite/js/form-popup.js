jQuery(function ($) {
    if ($(window).width() > 1023) {

        //Wrap the setTimeout into an if statement
        setTimeout(() => {
            document.addEventListener('mouseout', mouseEvent);
        }, 2_000);

        const mouseEvent = e => {
            const shouldShowExitIntent =
                !e.toElement &&
                !e.relatedTarget &&
                e.clientY < 10;

            if (shouldShowExitIntent)  {
                document.removeEventListener('mouseout', mouseEvent);
                if (sessionStorage.getItem('exit-popup') || document.cookie.match(/^(.*;)?\s*exit-popup\s*=\s*[^;]+(.*)?$/)){
                    // console.log('yes');
                }
                else {
                    $('#conFormExitPopup').modal();
                    // console.log('no');
                }
            }
        };

        $('#modal-close-icon').click(function (){
            $('#conFormExitPopup').modal('hide');
        });

        $('#conFormExitPopup').on('hide.bs.modal', function (e) {
            sessionStorage.setItem('exit-popup', 'done');
        })

        // popup inner click functionality
        $('.con-form-after').hide();
        $('.ContactNow').on('click', function(){
            $('.con-form-before').hide();
            $('.con-form-after').show();
            sessionStorage.setItem('exit-popup', 'done');
            // console.log('popup-done');
        });

    }
});

var currSeconds = 0;
var confirmOneExecute = false;
jQuery(function ($) {
    if (!$('body').is('.lp-2022, .landing-page')) {
        if ($(window).width() > 1023) {
            if (sessionStorage.getItem('exit-popup') || document.cookie.match(/^(.*;)?\s*exit-popup\s*=\s*[^;]+(.*)?$/)) {
                // console.log('yes');
            } else {
                /* Increment the idle time
                    counter every second */
                let idleInterval =
                    setInterval(timerIncrement, 1000);

                /* Zero the idle timer
                    on mouse movement */
                $(this).mousemove(resetTimer);
                $(this).keypress(resetTimer);

                function resetTimer() {
                    currSeconds = 0;
                }

                function timerIncrement() {
                    currSeconds = currSeconds + 1;
                    if (confirmOneExecute === false) {
                        if (currSeconds > 80) {
                            confirmOneExecute = true;
                            //console.log('after 60 sec con popuop');
                            $('#conFormExitPopup').modal();
                            sessionStorage.setItem('exit-popup', 'done');
                            // console.log('popup-done');
                        }
                    }
                }

                // console.log('no');
            }
        }
    }
});