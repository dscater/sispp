<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notificaciones - Ver Notificación</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <strong>Nro.:</strong>
                                            <span
                                                v-text="oNotificacion?.id"
                                            ></span>
                                        </p>
                                        <p>
                                            <strong>Descripción:</strong>
                                            <span
                                                v-text="
                                                    oNotificacion?.descripcion
                                                "
                                            ></span>
                                        </p>
                                        <p>
                                            <strong>Indumentaria:</strong>
                                            <span
                                                v-text="
                                                    oNotificacion?.indumentaria
                                                "
                                            ></span>
                                        </p>
                                        <p>
                                            <strong>Fecha:</strong>
                                            <span
                                                v-text="oNotificacion?.fecha"
                                            ></span>
                                        </p>
                                        <p>
                                            <strong>Hora:</strong>
                                            <span
                                                v-text="oNotificacion?.hora"
                                            ></span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <router-link
                                            :to="{
                                                name: 'notificacions.index',
                                            }"
                                            class="btn btn-default btn-block"
                                            ><i class="fa fa-arrow-left"></i>
                                            Volver</router-link
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    props: ["id"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            oNotificacion: null,
        };
    },
    watch: {
        id(newVal) {
            this.getNotificacion();
        },
    },
    mounted() {
        this.loadingWindow.close();
        this.getNotificacion();
    },
    methods: {
        // Listar Notificacions
        getNotificacion() {
            let url = "/admin/notificacions/" + this.id;
            axios.get(url).then((response) => {
                this.oNotificacion = response.data;
            });
        },
    },
};
</script>

<style></style>
