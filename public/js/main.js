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

    $.fn.datepicker.dates['pt-BR'] = {
        days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        daysMin: ["Do", "Se", "Te", "Qa", "Qi", "Sx", "Sa"],
        months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
        today: "Hoje",
        clear: "Limpar",
        format: "dd/mm/yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 0
    };

    $('.datepicker').datepicker({
      language: 'pt-BR',
      autoclose: true
    })

    $('.select2').select2({
        language: "pt-BR"
    });

    $('[data-mask]').inputmask();
});
