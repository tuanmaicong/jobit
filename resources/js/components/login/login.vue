<template>
  <VeeForm as="div" v-slot="{ handleSubmit }" @invalid-submit="onInvalidSubmit">
    <div class="account-popup">
      <span class="close-popup"><i class="la la-close"></i></span>
      <h3>Đăng nhập</h3>
      <form
        @submit="handleSubmit($event, onSubmit)"
        ref="formData"
        method="POST"
        :action="data.urlStore"
      >
        <input type="hidden" :value="csrfToken" name="_token" />
        <div class="cfield">
          <Field
            type="email"
            name="email"
            v-model="model.email"
            rules="required|email"
            placeholder="Email"
          />
          <i class="la la-user"></i>
        </div>
        <ErrorMessage class="error" name="email" /> <br />

        <div class="cfield">
          <Field
            type="password"
            v-model="model.password"
            name="password"
            rules="required|min:8|max:16"
            placeholder="********"
          />
          <i class="la la-key"></i>
        </div>
        <ErrorMessage class="error ml-2" name="password" /> <br />
        <a href="#" title="">Quên mật khẩu?</a>
        <button type="submit">Đăng nhập</button>
      </form>
      <div class="extra-login">
        <span>Or</span>
        <div class="login-social">
          <a class="fb-login" href="#" title=""
            ><i class="fa fa-facebook"></i
          ></a>
          <a class="tw-login" href="#" title=""
            ><i class="fa fa-twitter"></i
          ></a>
        </div>
      </div>
    </div>
  </VeeForm>
</template>
  
<script>
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure,
} from "vee-validate";
import { localize } from "@vee-validate/i18n";
import * as rules from "@vee-validate/rules";
import $ from "jquery";
import axios from "axios";
export default {
  setup() {
    Object.keys(rules).forEach((rule) => {
      if (rule != "default") {
        defineRule(rule, rules[rule]);
      }
    });
  },
  components: {
    VeeForm,
    Field,
    ErrorMessage,
  },
  props: ["data"],
  data: function () {
    return {
      csrfToken: Laravel.csrfToken,
      model: {},
    };
  },
  mounted() {
    console.log(this.data);
  },
  created() {
    let messError = {
      en: {
        fields: {
          email: {
            required: "Email không được để trống",
            email: "Email không đúng định dạng",
          },

          password: {
            required: "Password không được để trống",
            min: "Mật khẩu dài từ 8 đến 16 ký tự",
            max: "Mật khẩu dài từ 8 đến 16 ký tự",
          },
        },
      },
    };
    configure({
      generateMessage: localize(messError),
    });
  },
  methods: {
    onInvalidSubmit({ errors }) {
      let firstInputError = Object.entries(errors)[0][0];
      this.$el.querySelector("input[name=" + firstInputError + "]").focus();
      $("html, body").animate(
        {
          scrollTop:
            $("input[name=" + firstInputError + "]").offset().top - 150,
        },
        500
      );
    },

    onSubmit() {
      this.$refs.formData.submit();
    },
  },
};
</script>
<style>
.error {
  color: red;
}
</style>
  