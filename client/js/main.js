
var app=new Vue({

    el: '#app',
    data () {
        return {
            info: [],
            loading: true,
            errored: false
        }
    },
    methods: {
        loadData()
        {
            var $apiEndpoint =  settingObject.root + 'salat/v1/time?_wpnonce=' + settingObject.nonce;
            axios.get($apiEndpoint)
                .then((response) =>
                {this.info = response;
                    console.log(response);})
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => this.loading = false)
        },
        saveChanges()
        {
            this.onSubmit();
        },
        onSubmit () {
           //alert(settingObject.root );
           console.log(settingObject.nonce);

            var $apiEndpoint =  settingObject.root + 'salat/v1/time?_wpnonce=' + settingObject.nonce;
            $jsonData=JSON.stringify(this.info.data);
            console.log($jsonData);
////////////////////////////////post data////////////////////
////////////////////////////////post data////////////////////

            var jqxhr = jQuery.post( $apiEndpoint,$jsonData)
                .done(function(response) {
                    //window.app.controls.response.show = true;
                    if(response.status == 'success' || response.status == 'error') {
                       this.loadData();
                    }
                    else {
                        // window.app.controls.response.status = 'error';
                        // window.app.controls.response.message = 'Please close the browser and try again or call us on 1800 200 500';
                    }
                })
                .fail(function(response) {
                    // window.app.controls.response.show = true;
                    // window.app.controls.response.status = 'error';
                    // window.app.controls.response.message = 'Please close the browser and try again or call us on 1800 200 500';
                    //alert( response );
                })
                .always(function(response) {
                    // window.app.controls.isSubmitting = false;
                    //console.log( "done" );
                });
        }

     },
    mounted () {
       this.loadData();
        setTimeout(this.loadData, 5*60*1000);
    }
})
