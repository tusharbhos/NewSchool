<!-- General JS Scripts -->
 <script src="{{ asset('back-end/js/app.min.js') }}"></script>
 <!-- JS Libraies -->
 <script src="{{ asset('back-end/bundles/apexcharts/apexcharts.min.js') }}"></script>

 <script src="{{ asset('back-end/bundles/jquery-ui/jquery-ui.min.js') }}"></script>


 <script src="{{ asset('back-end/bundles/cleave-js/dist/cleave.min.js') }}"></script>
  <script src="{{ asset('back-end/bundles/cleave-js/dist/addons/cleave-phone.us.js') }}"></script>
  <script src="{{ asset('back-end/bundles/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
<script src="{{ asset('back-end/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>

 <!-- Page Specific JS File -->
 <script src="{{ asset('back-end/js/page/index.js') }}"></script>

<script src="{{ asset('back-end/bundles/select2/dist/js/select2.full.min.js') }}"></script>

 <script src="{{ asset('back-end/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>

<script src="{{ asset('back-end/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back-end/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

  <script src="{{ asset('back-end/bundles/owlcarousel2/dist/owl.carousel.min.js') }}"></script>

<script src="{{ asset('back-end/bundles/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

 <script src="{{ asset('back-end/js/page/datatables.js') }}"></script>

  <!-- JS Libraies -->
  <script src="{{ asset('back-end/bundles/izitoast/js/iziToast.min.js') }}"></script>

  <script src="{{ asset('back-end/bundles/sweetalert/sweetalert.min.js') }}"></script>
  
  <!-- Page Specific JS File -->
  <script src="{{ asset('back-end/js/page/toastr.js') }}"></script>
  
 <!-- Template JS File -->
 <script src="{{ asset('back-end/js/scripts.js') }}"></script>
 <!-- Custom JS File -->
 <script src="{{ asset('back-end/js/custom.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            $(".alert").remove();
        }, 5000); // 5 secs
    });

    @if(session()->has('message'))
        swal('{{ session('message')['title'] }}', '{{ session('message')['message'] }}', '{{ strtolower(session('message')['type']) }}');
    @endif

    function updateCharacterCount(element) {
        const maxLength = element.maxLength;
        const currentLength = element.value.length;
        const remaining = maxLength - currentLength;

        const characterCountElement = document.getElementById('characterCount');
        characterCountElement.textContent = `Characters remaining: ${remaining}`;
    }
</script>