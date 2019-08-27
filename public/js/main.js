$(document).ready(function() {
    $("#select-all").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(".massDelete").click(function(){
        Swal.fire({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: cancel,
            confirmButtonText: confirm
        }).then((result) => {
            if (result.value) {
                form = $( "<form />", { action: route, method:'POST' } );
                form.append('<input name="_token" type="text" value="' + token + '">');
                $.each( $("input[name='ids[]']:checked"), function() {
                    form.append('<input name="ids[]" type="text" value="'+$(this).val()+'">');
                } );
                $('body').append(form);
                form.submit();
            }
        })
    });

    $('.select2').select2({
        language: "pt-BR"
    });

    $('[data-mask]').inputmask();
});
