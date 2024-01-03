<template>
    <div>
        <form id="Formpermiso" class="form-horizontal" method="post" autocomplete="off">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <b-overlay :show="showloader" rounded="sm">
                            <VuePdfApp style="height: 100vh" :config="config3" :pdf="FilePDF" />
                            <!--<embed :src="FilePDF+'#toolbar=0&navpanes=0&scrollbar=0'" width="100%" height="800px" type="application/pdf">-->
                        </b-overlay>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    export default {
        name:  "CPdf",
        props: ["IdProp","Url"],
        data() {
            return {
                FilePDF:      null,
                showloader:   false,
                errorWrapper: false,
                APIS_URL:     '',
                IdR:          0,
                objcontrol: {
                    Archivo: "",
                },
                config3: {
                    sidebar:          false,
                    secondaryToolbar: false,
                    toolbar: {
                        toolbarViewerLeft:   false,
                        toolbarViewerRight:  false,
                        toolbarViewerMiddle: false,
                    },
                },
            };
        },
        methods: {
            get_one() {
                // console.log('IdR:'+this.IdR);
                this.showloader = true;
                this.$http.get(this.APIS_URL, {
                    responseType: "blob",
                    params:{
                        IdR:this.IdR,
                    }
                })
                .then((response) => {
                    this.FilePDF    = '';
                    this.showloader = false;
                    let pdfContent  = response.data;
                    let file        = new Blob([pdfContent], { type: "application/pdf" });
                    this.FilePDF    = URL.createObjectURL(file);
                    this.openHandler();
                    //window.open(fileUrl);
                })
                .catch((err) => {
                    this.showloader = false;
                });
            },
            openHandler(pdfApp) {
                window._pdfApp = pdfApp;
            },
        },
        beforeDestroy() {
            this.FilePDF              = null;
            this.APIS_URL             = '';
            this.objcontrol.IdControl = 0;
        },
        created() {

            if (this.IdProp !== undefined && this.IdProp !== '') {
                this.IdR = this.IdProp; // console.log(item); // console.log('IdProp - OK: '+this.IdProp);

                if (this.Url !== undefined && this.Url !== '') { // console.log('Url - OK: '+this.Url);
                    this.APIS_URL = this.Url;
                    this.get_one();
                }

            }

        },
    };

</script>
