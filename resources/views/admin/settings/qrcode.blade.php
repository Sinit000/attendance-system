        <div>

        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(80)->generate('ban/'.$id)) !!} ">
            <!-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(80)->generate('http://banban-hr.herokuapp.com/'.$id)) !!} "> -->
    </div>
        
        <!-- <script type="text/javascript">
            var id = $('data-id').val();
            var name = $('data-one').val();
            var qrcode = QrCode(document.getElementById("qrcode"), {
                text: id,
                title: name,

                titleFont: "bold 16px Arial",
                titleColor: "#000000",
                titleBackgroundColor: "#ffffff",
                titleHeight: 35,


                width: 100,
                height: 100,

            });
        </script> -->