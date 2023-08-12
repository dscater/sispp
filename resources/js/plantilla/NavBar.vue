<template>
    <div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-primary">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        href="#"
                        class="nav-link text-white"
                        data-widget="pushmenu"
                        role="button"
                        ><i class="fas fa-bars"></i
                    ></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <router-link
                        :to="{ name: 'inicio' }"
                        class="nav-link text-white"
                        >Inicio</router-link
                    >
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li
                    class="nav-item dropdown"
                    v-if="permisos.includes('notificacions.index')"
                >
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell text-white text-md"></i>
                        <span
                            class="badge badge-danger navbar-badge"
                            v-if="sin_ver > 0"
                            v-text="sin_ver"
                        ></span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    >
                        <span class="dropdown-item dropdown-header"
                            ><span v-text="total_notificaciones"></span>
                            Notificaciones</span
                        >
                        <div class="dropdown-divider"></div>
                        <div class="contenedor_notificacions">
                            <template
                                v-for="(item, index) in listNotificacions"
                            >
                                <router-link
                                    class="dropdown-item notificacion"
                                    :class="[
                                        item.visto == 0 ? 'bg-danger' : '',
                                    ]"
                                    :to="{
                                        name: 'notificacions.show',
                                        params: {
                                            id: item.id,
                                        },
                                    }"
                                >
                                    <i class="fas fa-file mr-2"></i>
                                    <span class="desc_notificacion">{{
                                        item.descripcion
                                    }}</span>
                                    <span
                                        class="float-right text-muted text-sm"
                                        :class="[
                                            item.visto == 0 ? 'text-white' : '',
                                        ]"
                                        >{{ item.hace }}</span
                                    >
                                </router-link>
                                <div class="dropdown-divider"></div>
                            </template>
                        </div>
                        <router-link
                            :to="{
                                name: 'notificacions.index',
                            }"
                            class="dropdown-item dropdown-footer"
                            >Ver todas las notificaciones</router-link
                        >
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link text-white"
                        href="#"
                        role="button"
                        @click.prevent="logout()"
                        v-loading.fullscreen.lock="fullscreenLoading"
                    >
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link text-white"
                        data-widget="fullscreen"
                        href="#"
                        role="button"
                    >
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            fullscreenLoading: false,
            user: JSON.parse(localStorage.getItem("user")),
            permisos: localStorage.getItem("permisos"),
            total_notificaciones: 0,
            sin_ver: 0,
            listNotificacions: [],
        };
    },
    mounted() {
        if (!this.permisos) {
            this.$router.push({ name: "login" });
        }
        this.getNotificaciones();
        setInterval(this.getNotificaciones, 2000);
    },
    methods: {
        logout() {
            this.fullscreenLoading = true;
            axios.post("/logout").then((res) => {
                setTimeout(function () {
                    localStorage.clear();
                    location.reload();
                    this.$router.push({ name: "login" });
                }, 500);
            });
        },
        getNotificaciones() {
            axios.get("/admin/notificacions/orderByLast").then((response) => {
                this.listNotificacions = response.data.notificacions;
                this.sin_ver = response.data.no_vistos;
                this.total_notificaciones = response.data.total;
            });
        },
    },
};
</script>

<style>
.contenedor_notificacions {
    width: 100%;
    max-height: 40vh;
    overflow: auto;
}
</style>
