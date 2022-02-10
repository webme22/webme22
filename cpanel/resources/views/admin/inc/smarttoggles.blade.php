<script>
    $('.smart-toggle').on('change', function () {
        let api_url = $(this).attr('data-value');
        let bodyFormData = new FormData();
        bodyFormData.append('_token', "{{csrf_token()}}");
        let element = $(this);
        swal(
            {
                title:"Are you sure?",
                text:"This will change the active status",
                type:"warning",
                showCancelButton:!0,
                confirmButtonClass:"btn-warning",
                confirmButtonText:"Yes, change it!",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm){
                    axios.post(api_url, bodyFormData).then(response => {
                        if (response && response.status === 200){
                            console.log(response);
                            swal(
                                {
                                    title:"Success",
                                    text:response.data.message,
                                    type:"success",
                                    confirmButtonClass:"btn-success",
                                })
                        }
                        else {
                            element.next($('.toggle-group')).click();
                            swal(
                                {
                                    title:"Oops",
                                    text:"Something is wrong with our servers, please try again.",
                                    type:"error",
                                    confirmButtonClass:"btn-danger",
                                });
                        }
                    });
                }
                else {
                    element.next($('.toggle-group')).click();
                }
            });
        return false;
    });
</script>
