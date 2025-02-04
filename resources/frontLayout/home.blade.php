<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هر چی دلت می خواد</title>
    <!-- bootstrap cdn -->
    @include('adminLayout.mainCssLinks')
    <script src="{{asset('BackEnd/js/jquery.min.js')}}"></script>
    @include('style.css.homeCSSLinks')
    {{-- @include('adminLayout.mainJSLinks') --}}
    <style>
        .left-arrow {
        cursor: pointer;
        }
        .left-arrow::before {
        content: '\2190'; /* Unicode for left arrow */
        margin-right: 8px;
        }
    </style>
</head>

<body>

   

    <!-- header  -->
    @include('frontLayout.homeHeader')
    <!-- items section  -->
    <section class="py-5">
        </div>
    </section>

    <!-- download section  -->
    @include('frontLayout.homeDownalod')
    @include('Modals.modalShowCites')
    <!-- footer  -->
    @include('frontLayout.homeFooter')

    <!-- links  -->
    <section>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <!-- aos -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <!-- my js flie -->
        <script src="js/app.js"></script>
    </section>
</body>
<script>
    $(document).on('click','#showCitesModal', function(){// this item has been defined in homeHeader Page
        // alert('Hello jimbo');
        document.getElementById('mdoalShowCites').style.display="block";
    })
    function CloseModal(ModalID){
        document.getElementById(ModalID).style.display="none";
    }
    // carousel img codes 
</script>

</html>