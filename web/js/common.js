// $('#btn-dropdown_header').click(function(){
//     if($(".dropdown-menu").is(':visible')){
//         $(".dropdown-menu ").hide();
//     } else {
//         $(".dropdown-menu ").show();
//     }
// });

// initTabletPicker();

// $('.multiple-input').on('afterAddRow', function(){
//     initTabletPicker();
// });

// $('#ajaxCrudModal').on('shown.bs.modal', function() {
//     setTimeout(function(){
//         initTabletPicker();

//         $('.multiple-input').on('afterAddRow', function(){
//             initTabletPicker();
//         });
//     }, 500);
// });


// function initTabletPicker()
// {
//     $('[type="date"]').each(function(){
//         $(this).data('value', $(this).val());
//         $(this).attr('placeholder', 'Выберите дату');
//     });

//     $('[type="date"]').pickadate({
//         format: 'dd.mm.yyyy',
//         formatSubmit: 'yyyy-mm-dd'
//     });

//     $('[type="datetime-local"]').each(function(){
//         $(this).data('value', $(this).val());
//         $(this).attr('placeholder', 'Выберите дату');
//     });

//     $('[type="datetime-local"]').pickadate({
//         format: 'dd.mm.yyyy',
//         formatSubmit: 'yyyy-mm-dd'
//     });
// }

function typeInTextarea(el, newText) {
    var start = el.prop("selectionStart")
    var end = el.prop("selectionEnd")
    var text = el.val()
    var before = text.substring(0, start)
    var after  = text.substring(end, text.length)
    el.val(before + newText + after)
    el[0].selectionStart = el[0].selectionEnd = start + newText.length
    el.focus()
}