<script>
    let token = "{{Session::get('admin_auth_token')}}";
    const config = {
        headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
    };
    axios.defaults.headers.common = config.headers;
    axios.defaults.baseURL = "{{config('app.url')}}";
    axios.defaults.withCredentials = true;
    const UNAUTHORIZED = 401;
    axios.interceptors.response.use(
        response => response,
        error => {
            const {status} = error.response;
            if (status === UNAUTHORIZED) {
                swal(
                    {
                        title:"Session expired",
                        text:"We will refresh the page for you.",
                        type:"error",
                        confirmButtonClass:"btn-danger",
                    }, function () {
                        location.href="{{route('admin.get_assure_login')}}";
                    });
                return Promise.reject(error);
            }
            else {
                return false;
            }
        }
    );
</script>
