$('.remove').on('click', function(){
    let pr = this.parentNode.parentNode,
        prId = pr.getAttribute('data-id');
    console.log(prId)
    if(confirm("Удалить данные формы id = " + prId + "?")){
        $.ajax({
            type: "POST",
            url: './delete_user.php',
            data: {'user_id': prId},
            
            success: function(e)
            {
                $(pr).find('td:not(.form_del)').remove();
                $(pr).find('.form_del').removeClass('hidden');
            },
            error: function(){
                alert('Ошибка');
            }
            
        });
    }
});