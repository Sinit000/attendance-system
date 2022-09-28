<script src="{{ asset('frontend/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/custom.js') }}"></script>
<script src="{{ asset('frontend/css/bootstrap4/popper.js') }}"></script>
<script src="{{ asset('frontend/css/bootstrap4/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#search').keyup(function() {
            if($(this).val() == '') {
                $('#btnSubmit').prop('disabled', true);
            } else {
                $('#btnSubmit').prop('disabled', false);
            }
        });

        $('#search-form').on('keyup change', '#result', function() {
            var test = $(this).val();
            if(test == '') {
                $('#search-form').prop('action', 'javascript:void(0)');
            } else {
                $('#search-form').prop('action', '{{ route('public.search') }}');
            }
        });

        $('form.example #result').focusin(function() {
            $(this).parents('#search-parent').prop('style', 'width:800px');
        });

        $('form.example #result').focusout(function() {
            $(this).parents('#search-parent').prop('style', '');
        });
    });
</script>
