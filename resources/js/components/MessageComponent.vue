<template>
    <div>
        <ul class="chat border-top bg-light" v-chat-scroll="{always: false}">
            <li class="left clearfix p-3" v-for="message in msg">
                <div class="chat-body clearfix">
                    <div class="row" v-if="(message.from == from) && (message.to == to)">
                        <div class="col-3 offset-9 bg-primary p-2 sent">
                            <p>
                                {{ message.message }}
                            </p>
                        </div>
                    </div>
                    <div class="row" v-else="(message.from == to) && (message.to == from)">
                        <div class="col-3 received p-2">
                            <p>
                                {{ message.message }}
                            </p>
                        </div>
                    </div>

                </div>
            </li>
        </ul>
        <div class="badge badge-pill badge-primary" v-show="typing">typing...</div>
        <div class="badge badge-pill badge-danger" v-show="deleting">deleting...</div>
        <div class="input-group mt-3">
            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keydown="isTyping()" @keyup.delete="isDeleting()" @keyup.enter="sendMessage">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm form-control" id="btn-chat" @click="sendMessage">
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
                typing: false,
                deleting: false
            }
        },
        mounted() {
            let _this = this;
            let chat1 = _this.from;
            let chat2 = _this.to;
            if (_this.from > _this.to)
            {
                chat1 = _this.to;
                chat2 = _this.from;
            }
            Echo.private(`chat.${chat1}_${chat2}`)
                .listen('MessageSentEvent', (e) => {
                        this.msg.push({
                            message: e.message.message,
                        });
                })
                .listenForWhisper('typing', (e) => {
                    console.log(e.keyCodes);
//                    if (e.keyCodes)
                    this.typing = e.typing;
                    setTimeout(function() {
                        _this.typing = false;
                    }, 5000);
                })
                .listenForWhisper('deleting', (e) => {
                    this.deleting = e.deleting;
                    console.log(e.deleting);
                    setTimeout(function() {
                        _this.deleting = false;
                    }, 5000);
                })
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
                let _this = this;
                let chat1 = _this.from;
                let chat2 = _this.to;
                if (_this.from > _this.to)
                {
                    chat1 = _this.to;
                    chat2 = _this.from;
                }
                let channel = Echo.private(`chat.${chat1}_${chat2}`);
                    setTimeout(function() {
                        channel.whisper('typing', {
                            name: this.newMessage,
                            user: this.user,
                            typing: true
                        });
                    }, 300);

            },
            isDeleting() {
                let _this = this;
                let chat1 = _this.from;
                let chat2 = _this.to;
                if (_this.from > _this.to)
                {
                    chat1 = _this.to;
                    chat2 = _this.from;
                }
                let channel = Echo.private(`chat.${chat1}_${chat2}`);
                setTimeout(function() {
                    channel.whisper('deleting', {
                        name: this.newMessage,
                        user: this.user,
                        deleting: true
                    });
                }, 300);
            }
        },

    };
</script>