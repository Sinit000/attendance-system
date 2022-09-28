function insertCommentToDB(date){
    $.ajax({
        method: "POST",
        url: "workdays/"+ date ,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: date
    })
        .done(function( msg ) {
            // console.log('te');
            console.log(msg);
            // console.log('call insert db');
        });
}