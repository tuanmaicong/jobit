<template>
  <div>
    <div class="col-12 recuitment-inner">
      <div class="card-create-employer">
        <VeeForm
          as="div"
          v-slot="{ handleSubmit }"
          @invalid-submit="onInvalidSubmit"
        >
          <form
            class="recuitment-form"
            @submit="handleSubmit($event, onSubmit)"
            ref="formData"
            method="POST"
            :action="data.urlAccuracyCompany"
            enctype="multipart/form-data"
          >
            <Field type="hidden" :value="csrfToken" name="_token" />
            <div class="row">
              <div class="col-lg-12">
                <div class="billing-form-item">
                  <!-- billing-title-wrap -->
                  <div class="billing-content">
                    <div class="contact-form-action">
                      <div class="MultiFile-intercepted">
                        <div class="row">
                          <div class="col-12 form-update-company">
                            <div class="row">
                              <div class="col-12">
                                <div class="mb-3 row">
                                  <div class="row box-title-license">
                                    <p class="title-license">
                                      Trạng thái:
                                      <span
                                        class="badge bg-secondary"
                                        v-if="statusImage == 0"
                                        >Chưa xác thực</span
                                      >
                                      <span
                                        class="badge bg-success"
                                        v-if="statusImage == 1"
                                        >Đã xác thực</span
                                      >
                                    </p>
                                    <p class="title-license">
                                      Giấy phép kinh doanh:
                                    </p>
                                    <p class="title-license">
                                      Dung lượng file không vượt quá 5MB
                                    </p>
                                  </div>

                                  <div class="text-center">
                                    <div
                                      class="img-fluid border box-upload p-3"
                                      id="img-preview"
                                      @click="chooseImage()"
                                      role="button"
                                      style="
                                        height: 200px;
                                        width: 200px;
                                        object-fit: cover;
                                      "
                                      v-if="statusImage == 0"
                                    >
                                      <div style="display: none">
                                        <input
                                          type="file"
                                          @change="onChange"
                                          ref="fileInput"
                                          accept="image/*"
                                          name="images"
                                        />
                                      </div>
                                      <div
                                        id="img-preview"
                                        @click="chooseImage()"
                                        role="button"
                                      >
                                        <div style="display: none">
                                          <input
                                            type="file"
                                            id="file"
                                            @change="onChange"
                                            ref="fileInput"
                                            accept="image/*"
                                            name="images"
                                          />
                                        </div>
                                        <img
                                          v-if="filePreview || Image == null"
                                          :src="filePreview"
                                          class="img-fluid img-show border"
                                          style="
                                            width: 200px;
                                            height: 200px;
                                            object-fit: cover;
                                          "
                                        />
                                        <img
                                          v-if="Image != null && !filePreview"
                                          :src="url + '/' + Image"
                                          class="img-fluid img-show border"
                                          style="
                                            width: 200px;
                                            height: 200px;
                                            object-fit: cover;
                                          "
                                        />
                                      </div>
                                    </div>
                                    <div
                                      class="text-center border"
                                      style="
                                        width: 220px;
                                        height: 220px;
                                        margin-left: 20px;
                                      "
                                      v-if="statusImage == 1"
                                    >
                                      <img
                                        v-if="Image != null"
                                        :src="url + '/' + Image"
                                        class="img-fluid"
                                        style="
                                          width: 200px;
                                          height: 200px;
                                          margin-top: 10px;
                                          object-fit: cover;
                                        "
                                      />
                                    </div>
                                  </div>
                                  <ErrorMessage class="error" name="images" />
                                  <input
                                    type="hidden"
                                    name="images"
                                    :value="model.images"
                                  />
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12" v-if="statusImage == 0">
                              <button
                                type="submit"
                                class="btn min-width btn btn-primary btn-lg"
                              >
                                <!---->
                                Cập nhật
                              </button>
                            </div>
                          </div>
                        </div>
                        <!-- end row -->
                      </div>
                    </div>
                    <!-- end contact-form-action -->
                  </div>
                  <!-- end billing-content -->
                </div>
                <!-- end billing-form-item -->

                <!-- end billing-form-item -->
              </div>
              <!-- end col-lg-12 -->
            </div>
          </form>
        </VeeForm>
      </div>
      <loader :flag-show="flagShowLoader"></loader>
    </div>
  </div>
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
import Loader from "./../../common/loader.vue";
import moment from "moment";
export default {
  setup() {
    Object.keys(rules).forEach((rule) => {
      if (rule != "default") {
        defineRule(rule, rules[rule]);
      }
    });
  },
  data: function () {
    return {
      csrfToken: Laravel.csrfToken,
      url: Laravel.baseUrl,
      model: this.data.Company ?? "",
      filePreview: "",
      Image: null,
      statusImage: 0,
      flagShowLoader: false,
    };
  },
  props: ["data"],
  created() {
    console.log(this.data);
    if (this.data.Company) {
      this.dataCompany = this.data.Company;
    }
    if (this.data.accuracy) {
      this.Image = this.data.accuracy.images;
      if (this.data.accuracy.status == 0) {
        this.statusImage = 0;
      } else {
        this.statusImage = 1;
      }
    }
    let messError = {
      en: {
        fields: {
          file_cv: {
            required: "Ảnh không được để trống",
            mimes: "Ảnh chỉ hỗ trợ dạng jpeg,png,jpg,gif,svg,pdf,doc,docx",
            max: "kích thước ảnh quá lớn",
          },
        },
      },
    };
    configure({
      generateMessage: localize(messError),
    });
  },
  components: {
    VeeForm,
    Field,
    ErrorMessage,
    Loader,
  },
  methods: {
    moment: function () {
      return moment();
    },
    chooseImage() {
      this.$refs["fileInput"].click();
    },
    onChange(e) {
      let fileInput = this.$refs.fileInput;
      let imgFile = fileInput.files;
      if (imgFile && imgFile[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.filePreview = e.target.result;
        };
        reader.readAsDataURL(imgFile[0]);
      }
    },
    onInvalidSubmit({ values, errors, results }) {
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
      this.flagShowLoader = true;
      this.$refs.formData.submit();
    },
    previewImage: function (event) {
      var input = event.target;
      if (input.files) {
        var reader = new FileReader();
        reader.onload = (e) => {
          this.preview = e.target.result;
        };
        this.image = input.files[0];
        reader.readAsDataURL(input.files[0]);
      }
    },
  },
};
</script>
  
<style lang="scss">
.recuitment-inner {
  .img-show {
    width: 180px !important;
    height: 170px !important;
  }

  .box-title-license {
    height: 130px;
    margin-left: 15px !important;
    margin-top: 15px;
  }

  .title-license {
    font-size: 16px;
    font-weight: 550;
    margin-left: -10px;
  }

  .box-upload {
    background: rgba(229, 247, 237, 0.1);
    border: 2px dashed #2eb85c;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 16px;
    margin-left: 15px !important;
    position: relative;
    text-align: center;
    transition: 0.4s;
  }

  .container,
  .container-fluid,
  .container-lg,
  .container-md,
  .container-sm,
  .container-xl {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
  }

  .card {
    border: none;
  }

  .title-update-employer {
    margin: 20px 0px 0px 30px;
    font-size: 20px;
    font-weight: 600;
  }

  .p-update-employer {
    margin: 0px 0px 10px 30px;
    font-size: 17px;
    color: #000;
  }

  .btn:not(:disabled):not(.disabled) {
    cursor: pointer;
  }

  .min-width.btn {
    min-width: 100px;
    font-size: 14px;
  }

  .btn-primary {
    color: #fff;
  }

  .btn-primary:hover {
    color: #fff;
  }

  .btn-group-lg > .min-width.btn,
  .min-width.btn-lg {
    min-width: 120px;
  }

  .mr-2,
  .mx-2 {
    margin-right: 0.7rem !important;
  }

  .btn-group-lg > .btn,
  .btn-lg {
    padding: 0.6rem 1rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.214rem;
  }

  .btn-secondary {
    color: #212529;
    background-color: #f3f4f5;
    border-color: #f3f4f5;
  }
}
</style>