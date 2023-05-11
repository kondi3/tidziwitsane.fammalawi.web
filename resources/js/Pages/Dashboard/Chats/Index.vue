<template>
  <section class="w-full h-screen flex items-start overflow-hidden">
    <!-- chat list -->
    <div class="w-1/4 h-full border-r bg-white">
      <div class="mt-8 px-5">
        <TextInput
          type="text"
          placeholder="Search"
          class="text-sm block w-full"
        />
      </div>

      <div class="mt-14">
        <p class="text-gray-500 px-4">Chats</p>

        <div class="mt-5">
          <div
            class="hover:bg-gray-100 transition-colors px-4"
            :class="{
              'bg-gray-100': selected.chat && selected.chat.uid === chat.uid
            }"
            v-for="(chat, index) in chats.data"
            :key="index"
          >
            <button
              class="block w-full text-left border-b py-3"
              :class="{
                'font-bold' : chat.messages[0].user_id !== user.id && chat.messages[0].status !== 3
              }"
              @click="(selected.chat = {uid: chat.uid, name: chat.name}), selected.messages = chat.messages"
            >
              <h4 class="text-gray-800">{{ chat.name }}</h4>
              <div class="flex justify-between items-center mt-1">
                <p class="flex-1 text-sm truncate">
                  <span class="font-medium" v-if="chat.messages[0].user_id === user.id">{{ user.name }}:</span>
                  {{ chat.messages[0].message }}
                </p>
                <span class="text-xs text-gray-500">
                  {{ dayjs(chat.messages[0].created_at).fromNow() }}
                </span>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- chat screen -->
    <div
      class="flex-1 h-full flex flex-col justify-between relative"
      v-if="selected.chat"
    >
      <!-- header -->
      <div
        class="sticky top-0 left-0 w-full flex justify-between items-center px-5 py-4 bg-gray-50 border-b"
      >
        <p class="font-bold tracking-wider">{{ selected.chat.name }}</p>

        <button
          @click="selected.chat = null, selected.messages = null"
          class="text-gray-500 bg-gray-200 px-2 py-1 rounded-md hover:bg-gray-300 transition-colors"
        >
          <i class="fa-solid fa-close"></i>
        </button>
      </div>

      <!-- messages -->
      <div class="flex-1 flex flex-col-reverse px-5 overflow-auto scroll-smooth pt-5">
        <div
          class="block h-auto pb-2 text-left"
          :class="{ 'text-right': message.user_id === user.id }"
          v-for="(message, index) in selected.messages"
          :key="index"
        >
          <span
            class="inline-block max-w-xl p-3 rounded-2xl bg-gray-200 rounded-bl-none box-content"
            :class="{
              'bg-sky-400 rounded-bl-2xl rounded-br-none':
                message.user_id === user.id,
            }"
          >
            <span class="text-left inline-block">{{ message.message }}</span>
          </span>
          <p
            class="text-xs text-gray-500 mt-2.5 px-2"
            :class="{ 'text-right': message.user_id === user.id }"
            v-if="index === 0"
          >
            {{ dayjs(message.created_at).fromNow() }}
          </p>
        </div>
      </div>

      <!-- footer -->
      <form @submit.prevent="() => sendMessage(selected.chat)" class="w-full">
        <div class="px-5 pb-3 flex items-end space-x-3">
          <TextAreaInput
            v-model="sendMessageForm.message"
            type="text"
            placeholder="Type your message..."
            class="block flex-1"
            rows="1"
            data-autoresize
            autofocus
          />
          <button
            type="submit"
            class="w-24 text-white uppercase tracking-wider bg-gray-700 py-2 hover:bg-gray-800 transition-colors rounded"
          >
            <i class="fa-solid fa-paper-plane mr-1.5 text-sm"></i>
            <span>Send</span>
          </button>
        </div>
      </form>
    </div>

    <div class="flex-1 h-full flex flex-col justify-center items-center" v-else>
      <div class="text-xl text-center text-gray-500">
        <i class="fa-solid fa-comment-slash"></i>
        <p>No chat openned.</p>
      </div>
    </div>
  </section>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
export default {
  layout: AuthenticatedLayout,
};
</script>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import { computed, onMounted, ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);

const user = usePage().props.auth.user;
const props = defineProps({ chats: Object });
const chats = computed(() => props.chats);
const selected = ref({
  chat: null,
  messages: null
});

const sendMessageForm = useForm({ message: null });

// auto input resizing
onMounted(function () {
  document.querySelectorAll("[data-autoresize]").forEach(function (element) {
    element.style.boxSizing = "border-box";
    var offset = element.offsetHeight - element.clientHeight;
    element.addEventListener("input", function (event) {
      event.target.style.height = "auto";
      event.target.style.height = event.target.scrollHeight + offset + "px";
    });
    element.removeAttribute("data-autoresize");
  });
});

const sendMessage = (chat) => {
  if (sendMessageForm.message === null || sendMessageForm.message === "") {
    return;
  }

  sendMessageForm.post(route("admin.chats.messages.store", chat.uid), {
    onSuccess: (e) => {
      const current_chat = chats.value.data.filter(x => x.uid === selected.value.chat.uid)[0]
      selected.value.messages = current_chat.messages
      sendMessageForm.reset();
    },
  });
};
</script>

<style scoped>
textarea {
  box-sizing: border-box;
  resize: none;
}
</style>