<template>
    <div class="consultando">

        <div class="cabecalho">
            <form class="formulario" :class="{ erro: error }" @submit.prevent="download">

                <div class="icone link"></div>

                <input class="texto" type="text" v-model="address" @input="validate" placeholder="Digite o endereço para baixar...">

                <div class="mensagem erro" v-if="error">{{message}}</div>

                <div class="botao" v-if="success" @click="download">
                    <span>Baixar</span>
                    <i class="icone baixar"></i>
                </div>

            </form>
        </div>

        <div class="corpo">
            <div class="lista">
                <router-link class="item" v-for="(item, key) in list" :key="key" :to="{ name: 'view', params: { id: item.id } }">
                    <div class="descricao">
                        <div class="codigo" :class="item.status">{{item.code}}</div>
                        <div class="endereco">{{item.address}}</div>
                        <div class="mensagem" v-if="item.message">{{item.message}}</div>
                    </div>
                    <div class="situacao">{{item.status}}</div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>

    import * as moment from 'moment';
    import validate from 'validate.js';

    moment.locale('pt-br');

    export default {
        data() {
            return {
                error: false,
                success: false,
                message: false,
                address: '',
            }
        },
        methods: {
            download()
            {
                this.validate().then((attributes) => {
                    this.$store.dispatch('ADD_URL', attributes).then(() => {
                        this.address = '';
                        this.success = false;
                    });
                });
            },
            validate()
            {
                const options = {
                    address: {
                        presence: {
                            message: 'Precisa digitar um endereço'
                        },
                        url: {
                            message: 'Endereço Inválido'
                        }
                    }
                };

                const params = {
                    address: this.address
                };

                const config = {
                    fullMessages: false,
                };
                
                return validate.async(params, options, config)
                    .then((attributes) => {
                        this.success = true;
                        this.error = false;
                        this.message = false;
                        return attributes;
                    }, (errors) => {
                        this.error = true;
                        this.success = false;
                        this.message = errors.address[0];
                        return errors;
                    });
            },
            view()
            {
            },
        },
        watch: {
            address(value)
            {
                if(value=='')
                {
                    this.error = false;
                }
            }
        },
        computed: {
            list()
            {
                return this.$store.state.urls.filter((item) => {

                    if(item.status == 'recebido')
                        item.message = 'Conteudo Baixado ' + moment(item.created_at).fromNow();

                    if(item.status == 'processando')
                        item.message = 'Processando ' + moment(item.created_at).fromNow();

                    return item;
                })
            }
        },
        mounted()
        {
        }
    }

</script>
