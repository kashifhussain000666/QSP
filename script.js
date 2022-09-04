$('#submit').click(function(e){
  e.preventDefault()
  var question = $('#question').val();
  var id = $('#id').val();
  var question_set = eval(window.location.search.substr(1))
  if(question == ''){
    $('#validation').html('Please attempt the question');
    $('#validation').addClass('error');
  }else{
    $('#submit').prop('disabled',true);
    $.ajax({
            url: "queries.php",
            type: "post",
            data: {
                    question:question,
                    id:id,
                    question_set_id: question_set,
                    question_submit:"set"
                  },
            beforeSend: function(){
              $('#validation').html('')
            },
            success: function (response) {
              var data = JSON.parse(response);
              if(data.result != null){
                $('#id').val(data.result.id);
                $('#question').val('')
                $('#question_label').html(data.result.question_text)
                $('#question_count').html(data.count);
                $('#validation').addClass('success')
                $('#validation').html('Question Saved Successfully')
              }else{
                window.open('result.php?message=Question set attempted&question_set='+question_set+'','_self')
              }
            },
            complete: function(){
              // $('#validation').html('')
              $('#submit').prop('disabled',false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
  }
});