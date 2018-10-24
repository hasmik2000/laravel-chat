<template>
    <div>
        <ul class="chat border-top bg-light" v-chat-scroll="{always: false}">
            <li class="left clearfix p-3" v-for="message in msg">
                <div class="chat-body clearfix">
                    <div class="row" v-if="(message.from == from) && (message.to == to)">
                        <div class="col-3 offset-9 bg-primary p-2 sent">
                            <!--<div class="header">-->
                                <!--<strong class="primary-font">-->
                                    <!--{{ message.user.name }}-->
                                <!--</strong>-->
                            <!--</div>-->
                            <p>
                                {{ message.message }}
                            </p>
                            <!--<span class="badge badge-pill badge-info">{{ message.user.name }}</span>-->
                        </div>
                    </div>
                    <div class="row" v-else="(message.from == to) && (message.to == from)">
                        <div class="col-3 received p-2">
                            <!--<div class="header">-->
                                <!--<strong class="primary-font">-->
                                    <!--{{ message.user.name }}-->
                                <!--</strong>-->
                            <!--</div>-->
                            <p>
                                {{ message.message }}
                            </p>
                            <!--<span class="badge badge-pill badge-info">{{ message.user.name }}</span>-->
                        </div>
                    </div>

                </div>
            </li>
        </ul>
        <div class="badge badge-pill badge-primary">{{ typing }}</div>
        <div class="input-group mt-3">
            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keydown="isTyping()" @keyup.enter="sendMessage">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                    Send
                </button>
            </span>
        </div>
    </div>
</template>

<script>


    export default {
        props: [
            'messages',
            'from',
            'to',
            'color',
            'user',
        ],
        data() {
            return {
                newMessage: '',
                msg: this.messages,
                typing: ''
            }
        },
        mounted() {
            Echo.private('chat')
                .listen('MessageSentEvent', (e) => {
                    if ((this.to === e.message.from) && (this.from === e.message.to)) {
                        this.msg.push({
                            message: e.message.message,
                            user:{name: e.user.name },
                        });
                        this.typing = ''
                    }
                })
                .listenForWhisper('typing', (e) => {
                    if (e.name != '') {
                        this.typing = 'typing...'
                    } else {
                        this.typing = ''
                    }

                });
        },
        methods: {
            sendMessage() {
                const _this = this;
                axios.post('/send', {
                    message: this.newMessage,
                    from: this.from,
                    to: this.to,
                })
                    .then(function (response) {
                        _this.msg.push({
                            message: _this.newMessage,
                            user: {name: _this.user.name},
                            from: _this.from,
                            to: _this.to
                        });
                        _this.newMessage = '';
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            isTyping() {
                Echo.private('chat')
                    .whisper('typing', {
                        name: this.newMessage
                    });
            },
        },

    };
</script>