<template>
    <div class="container">
        <!-- ... -->
        <div class="shortner">
            <!-- ... -->
            <form action="" class="form">
                <div class="input-group">
                    <input type="text" id="p1" placeholder="Enter URL" v-model="url"
                        class="form-control addUrlInput">
                </div>
                <br>
                <div>
                    <button class="btn btn-dark" v-on:click.prevent='shortenUrl'>
                        Shorten Url
                    </button>
                    <button class="btn btn-dark" v-on:click.prevent="copyContent">
                        {{ copyTextString }}
                    </button>
                </div>
            </form>
            <br>
            <p v-if="!urlNotFound" class="alert alert-danger">
                Url is not Valid
            </p>
            <div class="copyLink mb-5">
                <span id="output_url"></span>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props:[],
        data(){
            return {
                url:null,
                urlNotFound: true,
                copyTextString: 'Copy Text To Clipboard',
                response:null,
            }
        },
        methods:{
            shortenUrl(){
                let self = this;
                let newUrl = self.url;
                let newArray = newUrl.split('//');
                let counter = 0;
                let resultNewUrl = Math.round((Math.pow(36,8) - Math.random() * Math.pow(36, 8))).toString(36).slice(1);


                for(let i = 0; i < newArray.length; i++){
                    if(newArray[i] == 'http:' || newArray[i] == 'https:'){
                        counter++;
                    }


                    if(counter == 0){
                        let newArrayOne = newUrl.split('.');
                        if(newArrayOne[i] == 'www'){
                            counter++;
                        }


                        let newArrayTwo = newUrl.indexOf('.com');
                        if(newArrayTwo >= 0){
                            counter++;
                        }
                    }


                    if(counter ==0){
                        self.urlNotFound = false;
                    }else{
                        let currentUrl = window.location.href;

                        axios.post('/url/shorten',{
                            url: newUrl,
                            hash: resultNewUrl,
                            domain: currentUrl
                        }).then(function(response){
                            self.response = response.data;
                            $('.copyLink').fadeIn(500);
                            $('.copyLink').siblings('.form').find("#p1").val(self.response);
                            console.log(self.response);
                        });
                    }
                }
            },
            copyContent(){
                $("#p1").select();
                this.copyTextString = 'Url Copied Successfully';
                document.execCommand("copy");
                this.url = this.response;
            }
        }
    }
</script>
<style scoped>
    .copyLink{
        display:none;
    }


    #clipBoard{
        display:block;
        margin-top: 28px;
        background-color: #03cbf8;
        color:#fff;
        font-weight: 900;
        font-size:17px;
    }


    #clipBoard:hover{
        background-color:#333;
    }


    #clipBoard:visited, #clipBoard:active, #clipBoard:focus{
        background-color:green;
        color:#333;
    }


</style>
