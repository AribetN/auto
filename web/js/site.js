$(document).ready(function() {
    $('#cars-id_brand').change(function(){
        var $this = $(this);
        $.ajax({
            url: "/cars/models",
            cache: false,
            data: "id_brand="+$this.val(),
            dataType: "json",
            success: function(json){
                var options = '<option value="">Выберите модель</option>';
                $.each(json, function(id_model, name) {
                    options += '<option value="'+id_model+'">'+name+'</option>';
                });
                $('#cars-id_model').html(options);
            }
        });
    });
});