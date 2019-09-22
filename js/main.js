window.onload = function () {
    var app = new Vue({
        el: '#app',
        data: {
            requestdata: [
                name = '',
                phoneNum = '',
                message = '',
                storage = ''
            ],
            showform: true,
            responseform: false,
            responseMessage: 'asdasdas',
            isButtonDisabled: true
        },
        methods: {
            fetchRequest: function (requestdata) {
                console.log ('trewerwe');
                var option = {
                params: {
                    name: this.requestdata.name,
                    phoneNum: this.requestdata.phoneNum,
                    message: this.requestdata.message,
                    storage: this.requestdata.storage
                }}
                this.$http.get('back.php', option).then(function (response) {

                    console.log(response.body.response);
                    this.responseMessage = response.body.response;
                    this.showform = false;
                    this.responseform = true
                    
                }, console.log)
            },
            validateForm: function () {
                if (this.lenghtString(this.requestdata.name) > 2 && this.requestdata.phoneNum > 999999 && this.lenghtString(this.requestdata.message) > 7) {
                    this.isButtonDisabled = null;
                };
            },
            lenghtString: function (str) {
                let l = 0;
                str ? l = str.length : 0;
                return l;
            }
        },


    })
}