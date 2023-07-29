<template>
    <div class="content-wrapper" style="min-height: 1604.44px">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>404 Error Page</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                404 Error Page
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-page">
                        <h2 class="headline text-primary">404</h2>
                        <div class="error-content">
                            <h3>
                                <i
                                    class="fas fa-exclamation-triangle text-primary"
                                ></i>
                                Oops! Página no encontrada.
                            </h3>
                            <p>
                                No pudimos encontrar la página que estabas
                                buscando. Mientras tanto, puede volver a la
                                página de inicio.
                                <router-link :to="{ name: 'inicio' }"
                                    >Volver al inicio</router-link
                                >
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {};
</script>

<style></style>
