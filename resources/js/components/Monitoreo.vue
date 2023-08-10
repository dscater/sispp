<template>
    <div class="row contenedor_monitoreo">
        <div class="col-md-12">
            <div
                class="card card-primary"
                style="
                    transition: all 0.15s ease 0s;
                    height: inherit;
                    width: inherit;
                "
            >
                <div class="card-header">
                    <h3 class="card-title">
                        MONITOREO DE CONTROL DE PROTECCIÓN DE PERSONAL
                    </h3>
                    <div class="card-tools">
                        <button
                            type="button"
                            class="btn btn-tool"
                            data-card-widget="maximize"
                        >
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body bg-primary text-center">
                                    <h4 class="text-center">ESTADO</h4>
                                    <template v-if="notificacion && notificacion.id">
                                        <span
                                            class="text-md badge"
                                            :class="[
                                                notificacion.tipo == 'NORMAL'
                                                    ? 'badge-success'
                                                    : 'badge-danger',
                                            ]"
                                            >{{ notificacion.tipo }}</span
                                        >
                                    </template>
                                    <span class="text-md badge bg-gray" v-else
                                        >SIN NOTIFICACIONES AÚN</span
                                    >
                                    <div class="contenedor_hora">
                                        <span>HORA: </span
                                        ><span v-text="hora"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div
                                    class="card-body bg-primary text-center imagen_monitoreo"
                                >
                                    <h4 class="text-center">IMAGEN</h4>
                                    <img
                                        v-if="notificacion && notificacion.id"
                                        :src="notificacion.path_image"
                                        alt=""
                                    />
                                    <div class="vacio" v-else></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            fullscreenLoading: true,
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            user: JSON.parse(localStorage.getItem("user")),
            notificacion: null,
            hora: "",
        };
    },
    mounted() {
        this.loadingWindow.close();
        setInterval(this.getHora, 1000);
        setInterval(this.getUltimaNotificacion, 1000);
    },
    methods: {
        getUltimaNotificacion() {
            axios
                .get("/admin/notificacions/getNuevaNotificacion")
                .then((response) => {
                    if (!response.data) {
                        this.notificacion = null;
                    } else {
                        if (!this.notificacion) {
                            this.notificacion = response.data;
                        } else {
                            if (this.notificacion.id != response.data.id) {
                                this.notificacion = response.data;
                            }
                        }
                    }
                });
        },
        getHora() {
            let now = new Date();
            let h = now.getHours();
            let m = now.getMinutes();
            let s = now.getSeconds();
            h = h < 10 ? "0" + h : h;
            m = m < 10 ? "0" + m : m;
            s = s < 10 ? "0" + s : s;
            this.hora = `${h}:${m}:${s}`;
        },
    },
};
</script>
<style>
.contenedor_monitoreo .contenedor_hora {
    margin-top: 10px;
    font-size: 1.2em;
    font-weight: bold;
}

.contenedor_monitoreo .vacio {
    height: 240px;
    min-width: 100%;
    background: black;
    margin: auto;
}

.contenedor_monitoreo .imagen_monitoreo img {
    width: 100%;
}
</style>
