<footer class="main-footer">
    <div class="footer-left">
        <div id="demo"></div>
        <!-- Copyright &copy; 2021 by Renny Suryani -->
    </div>
    <script>
        var y = "";
        var month = "";
        var day = "";
        var year = "";
        var all = "";

        function display_ct7() {
            var x = new Date()
            var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
            hours = x.getHours() % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().length == 1 ? 0 + hours.toString() : hours;

            var minutes = x.getMinutes().toString()
            minutes = minutes.length == 1 ? 0 + minutes : minutes;

            var seconds = x.getSeconds().toString()
            seconds = seconds.length == 1 ? 0 + seconds : seconds;

            var month = (x.getMonth() + 1).toString();
            month = month.length == 1 ? 0 + month : month;

            var dt = x.getDate().toString();
            dt = dt.length == 1 ? 0 + dt : dt;

            var x1 = month + "/" + dt + "/" + x.getFullYear();
            x1 = x1 + " - " + hours + ":" + minutes + ":" + seconds + " " + ampm;
            document.getElementById('demo').innerHTML = x1;

            display_c7();
            y = x1.substring(0, 10);
            month = y.substring(0, 2);
            day = y.substring(3, 5);
            year = y.substring(6, 10);
            all = month + day + year;
            // console.log('month');
            // console.log(month);
            // console.log(day);
            // console.log(year);
            // console.log(all);

            // insertCommentToDB(all);

        }

        function display_c7() {
            var refresh = 100; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct7()', refresh)
        }
        display_c7();
        // console.log(y);

        console.log('work');
        // $window.addEventListener('timeupdate', function() {
        //     alert('I changed');
        // });
    </script>
    <!-- <script>
        function showTime(){
            document.getElementById("demo").innerHTML = new Date();
        }
        showTime()
    </script>  
</footer>