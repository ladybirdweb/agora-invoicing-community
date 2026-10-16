<template>
    <div>

        <!-- Step 0: Server Type -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="card border-radius-1 bg-color-light box-shadow-6 box-shadow-hover cur-pointer h-100"
                     :class="{ 'border border-primary': form.deploy_mode === 'extract_only' }"
                     role="button" tabindex="0"
                     :aria-pressed="form.deploy_mode === 'extract_only'"
                     @click="form.deploy_mode = 'extract_only'"
                     @keydown.enter.space.prevent="form.deploy_mode = 'extract_only'">
                    <div class="card-body p-relative zindex-1 p-3">
                        <div class="feature-box feature-box-style-6 text-center d-block">
                            <div class="feature-box-icon justify-content-center">
                                <i class="fas fa-upload text-primary" aria-hidden="true"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="text-4 mt-3 mb-2 text-color-grey">{{ __('message.deploy_on_existing_server') }}</h4>
                                <p class="mb-0 text-2">{{ __('message.deploy_existing_server_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-radius-1 bg-color-light box-shadow-6 box-shadow-hover cur-pointer h-100"
                     :class="{ 'border border-primary': form.deploy_mode === 'fresh_install' }"
                     role="button" tabindex="0"
                     :aria-pressed="form.deploy_mode === 'fresh_install'"
                     @click="form.deploy_mode = 'fresh_install'"
                     @keydown.enter.space.prevent="form.deploy_mode = 'fresh_install'">
                    <div class="card-body p-relative zindex-1 p-3">
                        <div class="feature-box feature-box-style-6 text-center d-block">
                            <div class="feature-box-icon justify-content-center">
                                <i class="fas fa-server text-primary" aria-hidden="true"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="text-4 mt-3 mb-2 text-color-grey">{{ __('message.deploy_on_fresh_server') }}</h4>
                                <p class="mb-0 text-2">{{ __('message.deploy_fresh_server_desc') }}</p>
                                <span class="badge bg-warning text-dark mt-2">{{ __('message.deploy_time_estimate') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dw-actions">
            <a v-if="manualGuideUrl" :href="manualGuideUrl" target="_blank" rel="noopener noreferrer" class="dw-guide">
                {{ __('message.deploy_manual_guide_link') }}<i class="fas fa-external-link-alt ms-2" aria-hidden="true"></i>
            </a>
            <button type="button" class="btn btn-primary btn-modern dw-btn ms-auto" :disabled="!form.deploy_mode" @click="openWizard">
                {{ __('message.continue') }}
            </button>
        </div>

        <Modal :showModal="showModal" :onClose="closeModal" :showCloseBtn="false"
               :showControls="!(step === 3 && deploying)" classname="modal-lg">
            <template #title>
                <h5 class="modal-title fw-bold">
                    {{ form.deploy_mode === 'fresh_install' ? __('message.deploy_on_fresh_server') : __('message.deploy_on_existing_server') }}
                </h5>
                <p v-if="step <= 2" class="dw-subtitle mb-0">
                    {{ __('message.deploy_step_of', { current: step, total: 2, name: step === 1 ? __('message.connection') : __('message.installation_details') }) }}
                </p>
            </template>

            <template #fields>
                <AppAlert v-if="step <= 2" :componentName="COMPONENT" />

                <!-- Step 1: Connection -->
                <template v-if="step === 1">
                    <div class="row">
                        <div class="col-8 col-md-9">
                            <ClientField type="text" name="host" :label="__('message.deploy_host_address')" :required="true"
                                         v-model="form.host" placeholder="192.168.1.100 or example.com…" autocomplete="off" :spellcheck="false"
                                         :error="errors.host" @update:modelValue="setFieldError('host', undefined)" />
                        </div>
                        <div class="col-4 col-md-3">
                            <ClientField type="text" name="port" :label="__('message.port')" :required="true"
                                         :model-value="String(form.port)" placeholder="22" inputmode="numeric" autocomplete="off"
                                         :error="errors.port"
                                         @update:modelValue="form.port = Number($event); setFieldError('port', undefined)" />
                        </div>
                    </div>
                    <ClientField type="text" name="username" :label="__('message.username')" :required="true"
                                 v-model="form.username" placeholder="root or ubuntu…" autocomplete="off" :spellcheck="false"
                                 :error="errors.username" @update:modelValue="setFieldError('username', undefined)" />

                    <div class="mb-3">
                        <span :id="`${COMPONENT}-auth`" class="form-label text-dark d-block">{{ __('message.deploy_auth_method') }}</span>
                        <div class="btn-group dw-toggle" role="radiogroup" :aria-labelledby="`${COMPONENT}-auth`">
                            <template v-for="opt in authOptions" :key="opt.id">
                                <input :id="`${COMPONENT}-auth-${opt.id}`" type="radio" class="btn-check"
                                       name="auth_method" :value="opt.id" v-model="form.auth_method" />
                                <label class="btn btn-outline-primary" :for="`${COMPONENT}-auth-${opt.id}`">{{ opt.name }}</label>
                            </template>
                        </div>
                    </div>

                    <ClientField v-if="form.auth_method === 'password'" type="password" name="password"
                                 :label="__('message.deploy_ssh_password')" :required="true"
                                 v-model="form.password" autocomplete="new-password"
                                 :error="errors.password" @update:modelValue="setFieldError('password', undefined)" />
                    <ClientField v-else type="textarea" name="private_key" :label="__('message.deploy_ssh_private_key')" :required="true"
                                 v-model="form.private_key" :rows="4" :spellcheck="false"
                                 placeholder="-----BEGIN OPENSSH PRIVATE KEY-----"
                                 :error="errors.private_key" @update:modelValue="setFieldError('private_key', undefined)" />

                    <ClientField type="password" name="sudo_password"
                                 :label="`${__('message.deploy_sudo_password')} (${__('message.optional')})`"
                                 v-model="form.sudo_password" autocomplete="new-password"
                                 :error="errors.sudo_password" @update:modelValue="setFieldError('sudo_password', undefined)" />
                    <p class="form-text dw-hint">{{ __('message.deploy_sudo_hint') }}</p>

                    <p class="dw-note mb-0">
                        <i class="fas fa-lock text-primary me-2" aria-hidden="true"></i>{{ __('message.deploy_credentials_notice') }}
                    </p>
                </template>

                <!-- Step 2: Install details -->
                <template v-else-if="step === 2">
                    <template v-if="form.deploy_mode === 'extract_only'">
                        <ClientField type="text" name="deploy_path" :label="__('message.deploy_path_label')" :required="true"
                                     v-model="form.deploy_path" placeholder="/var/www/faveo…" autocomplete="off" :spellcheck="false"
                                     :error="errors.deploy_path" @update:modelValue="setFieldError('deploy_path', undefined)" />
                        <ClientField type="text" name="install_domain"
                                     :label="`${__('message.deploy_domain')} (${__('message.optional')})`"
                                     v-model="form.install_domain" placeholder="helpdesk.example.com…" autocomplete="off" :spellcheck="false" />
                    </template>

                    <template v-else>
                        <div class="row">
                            <div class="col-md-6">
                                <ClientField type="text" name="install_domain" :label="__('message.deploy_domain')" :required="true"
                                             v-model="form.install_domain" placeholder="helpdesk.example.com…" autocomplete="off" :spellcheck="false"
                                             :error="errors.install_domain" @update:modelValue="setFieldError('install_domain', undefined)" />
                            </div>
                            <div class="col-md-6">
                                <ClientField type="email" name="install_email" :label="__('message.deploy_admin_email')" :required="true"
                                             v-model="form.install_email" placeholder="admin@example.com…" autocomplete="off"
                                             :error="errors.install_email" @update:modelValue="setFieldError('install_email', undefined)" />
                            </div>
                            <div class="col-md-6">
                                <DynamicSelect name="web_server" :label="__('message.deploy_web_server')" :required="true"
                                               :elements="webServerOptions"
                                               :value="webServerOptions.find(o => o.id === form.web_server)"
                                               :onChange="(val) => form.web_server = val?.id ?? 1"
                                               :clearable="false" :searchable="false" />
                            </div>
                            <div class="col-md-6">
                                <DynamicSelect name="ssl_type" :label="__('message.deploy_ssl_certificate')" :required="true"
                                               :elements="sslOptions"
                                               :value="sslOptions.find(o => o.id === form.ssl_type)"
                                               :onChange="(val) => form.ssl_type = val?.id ?? 'A'"
                                               :clearable="false" :searchable="false" />
                            </div>
                            <template v-if="form.ssl_type === 'C'">
                                <div class="col-md-6">
                                    <ClientField type="text" name="ssl_cert_path" :label="__('message.deploy_certificate_path')" :required="true"
                                                 v-model="form.ssl_cert_path" placeholder="/etc/ssl/certs/cert.crt…" autocomplete="off" :spellcheck="false"
                                                 :error="errors.ssl_cert_path" @update:modelValue="setFieldError('ssl_cert_path', undefined)" />
                                </div>
                                <div class="col-md-6">
                                    <ClientField type="text" name="ssl_key_path" :label="__('message.deploy_key_path')" :required="true"
                                                 v-model="form.ssl_key_path" placeholder="/etc/ssl/private/cert.key…" autocomplete="off" :spellcheck="false"
                                                 :error="errors.ssl_key_path" @update:modelValue="setFieldError('ssl_key_path', undefined)" />
                                </div>
                            </template>
                        </div>
                    </template>

                    <div class="row">
                        <div class="col-md-6">
                            <DynamicSelect name="version_id" :label="__('message.version')" :required="true"
                                           :elements="versionOptions"
                                           :value="versionOptions.find(v => v.id === form.version_id) ?? versionOptions[0]"
                                           :onChange="(val) => form.version_id = val?.id ?? null"
                                           :disabled="loadingVersions" :clearable="false" />
                        </div>
                        <div class="col-md-6">
                            <ClientField type="text" name="web_user"
                                         :label="`${__('message.deploy_web_user')} (${__('message.optional')})`"
                                         v-model="form.web_user" :placeholder="__('message.deploy_web_user_placeholder')" autocomplete="off" :spellcheck="false" />
                        </div>
                    </div>
                </template>

                <!-- Step 3: Deploy (live progress) -->
                <template v-else-if="step === 3">
                    <ul class="list-unstyled mb-0" aria-live="polite">
                        <li v-for="s in plannedSteps" :key="s.key" class="d-flex align-items-start gap-2 mb-2"
                            :class="{ 'text-muted': !status[s.key], 'text-danger': status[s.key] === 'error' }">
                            <span v-if="status[s.key] === 'ok'" class="text-success"><i class="fas fa-check-circle" aria-hidden="true"></i><span class="visually-hidden">{{ __('message.done') }}</span></span>
                            <span v-else-if="status[s.key] === 'error'"><i class="fas fa-times-circle" aria-hidden="true"></i><span class="visually-hidden">{{ __('message.failed') }}</span></span>
                            <span v-else-if="status[s.key] === 'running'" class="text-primary"><i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i><span class="visually-hidden">{{ __('message.pending') }}</span></span>
                            <span v-else><i class="far fa-circle" aria-hidden="true"></i><span class="visually-hidden">{{ __('message.pending') }}</span></span>
                            <span>{{ s.label }}</span>
                        </li>
                    </ul>
                    <p v-if="deploying" class="small text-muted mt-3 mb-0">{{ __('message.deploy_running_notice') }}</p>
                    <div v-if="errorMessage" class="alert alert-danger mt-3 mb-0" role="alert">{{ errorMessage }}</div>
                </template>

                <!-- Step 4: Done -->
                <template v-else-if="step === 4">
                    <div class="text-center py-2">
                        <i class="fas fa-check-circle text-success fa-3x" aria-hidden="true"></i>
                        <h4 class="text-4 mt-3 mb-1">{{ __('message.deploy_setup_complete') }}</h4>
                        <p class="text-muted text-2">{{ __('message.deploy_success_desc') }}</p>
                    </div>
                    <template v-if="result.setup_url">
                        <hr>
                        <p class="mb-1 fw-semibold">{{ __('message.deploy_web_installer') }}</p>
                        <p class="text-muted small mb-2">{{ __('message.deploy_web_installer_desc') }}</p>
                        <a :href="result.setup_url" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-modern">
                            {{ __('message.deploy_visit_web_installer') }} <i class="fas fa-external-link-alt ms-1" aria-hidden="true"></i>
                        </a>
                    </template>
                    <template v-if="result.site_url">
                        <hr>
                        <p class="mb-1 fw-semibold">{{ __('message.deploy_detected_site_url') }}</p>
                        <a :href="result.site_url" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-modern">{{ result.site_url }}</a>
                    </template>
                    <template v-if="result.credentials">
                        <hr>
                        <p class="mb-1 fw-semibold">{{ __('message.deploy_admin_credentials') }}</p>
                        <p class="text-muted small mb-2">{{ __('message.deploy_save_securely') }}</p>
                        <pre class="bg-light p-3 rounded small">{{ result.credentials }}</pre>
                    </template>
                </template>
            </template>

            <template #controls>
                <template v-if="step === 1">
                    <action-button action="cancel" :disabled="checking" @click="closeModal" />
                    <action-button action="confirm" icon="fas fa-arrow-right" :label="__('message.next')" :loading="checking" @click="nextStep" />
                </template>
                <template v-else-if="step === 2">
                    <action-button action="back" @click="step = 1" />
                    <action-button action="confirm" :label="__('message.deploy')" :loading="deploying" @click="startDeploy" />
                </template>
                <template v-else-if="step === 3">
                    <action-button action="back" @click="step = 2" />
                </template>
                <template v-else-if="step === 4">
                    <action-button action="confirm" :label="__('message.done')" @click="resetWizard" />
                </template>
            </template>
        </Modal>

    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm } from 'vee-validate'
import http from '@/plugins/axios'
import { errorHandler } from '@/helpers/responseHandler.js'
import { validateForm } from '@/helpers/formUtils.js'
import Modal from '@/themes/porto/components/common/Modal.vue'
import AppAlert from '@/components/Reusable/Alert.vue'
import { deploySchema } from '@/validations/client/deployWizard.js'
import { __ } from '@/plugins/i18n'

const props = defineProps({
    orderId:        { type: Number, required: true },
    manualGuideUrl: { type: String, default: null },
})

const { errors, setErrors, setFieldError } = useForm()

const COMPONENT = 'deploy-wizard'

// 0 = closed, 1 = connection, 2 = install details, 3 = deploying, 4 = done
const step            = ref(0)
const showModal       = ref(false)
const deploying       = ref(false)
const checking        = ref(false)   // connection check on Next
const loadingVersions = ref(false)
const versions        = ref([])
const status          = ref({})   // server step key → 'running' | 'ok' | 'error'
const errorMessage    = ref('')
const result          = ref({})

const sslOptions = [
    { name: __('message.deploy_ssl_lets_encrypt'),     id: 'A' },
    { name: __('message.deploy_ssl_self_signed'),      id: 'B' },
    { name: __('message.deploy_ssl_paid_certificate'), id: 'C' },
]

const authOptions = [
    { name: __('message.password'),                  id: 'password' },
    { name: __('message.deploy_private_key_option'), id: 'private_key' },
]

const webServerOptions = [
    { name: 'Apache', id: 1 },
    { name: 'Nginx',  id: 2 },
]

const versionOptions = computed(() => [
    { id: null, name: __('message.latest') },
    ...versions.value.map(v => ({ id: v.id, name: `${v.version} — ${v.title}` })),
])

const form = reactive({
    deploy_mode:     '',
    host:            '',
    port:            22,
    username:        '',
    auth_method:     'password',
    password:        '',
    private_key:     '',
    sudo_password:   '',
    deploy_path:     '',
    install_domain:  '',
    install_email:   '',
    web_server:      1,
    ssl_type:        'A',
    ssl_cert_path:   '',
    ssl_key_path:    '',
    web_user:        '',
    version_id:      null,
})

// The server-side steps each mode runs, in order.
const plannedSteps = computed(() => form.deploy_mode === 'fresh_install'
    ? [
        { key: 'verify',  label: __('message.deploy_log_verifying_ssh') },
        { key: 'install', label: __('message.deploy_log_running_install') },
    ]
    : [
        { key: 'verify',  label: __('message.deploy_log_verifying_ssh') },
        { key: 'upload',  label: __('message.deploy_log_uploading_files') },
        { key: 'extract', label: __('message.deploy_log_extracting_files') },
    ])

// Step 1 only checks the connection fields; Deploy checks the whole form.
const connectSchema = deploySchema.pick(['host', 'port', 'username', 'password', 'private_key'])

async function openWizard() {
    step.value      = 1
    showModal.value = true
    if (versions.value.length === 0) {
        loadingVersions.value = true
        try {
            const res = await http.get(`/get-deploy-versions/${props.orderId}`)
            versions.value = res.data?.data ?? []
        } catch (e) {
            errorHandler(e, COMPONENT, { setErrors })
        } finally {
            loadingVersions.value = false
        }
    }
}

// Next = check the fields, then log in to the server once (no deploy path yet),
// so a wrong host/password shows up here instead of after the whole form.
async function nextStep() {
    if (!await validateForm(connectSchema, form, setErrors)) return
    checking.value = true
    try {
        await http.post(`/deploy-product-step`, {
            step:        'verify',
            order_id:    props.orderId,
            host:        form.host,
            port:        form.port,
            username:    form.username,
            auth_method: form.auth_method,
            password:    form.auth_method === 'password'    ? form.password    : undefined,
            private_key: form.auth_method === 'private_key' ? form.private_key : undefined,
            deploy_mode: form.deploy_mode,
        })
        step.value = 2
    } catch (e) {
        errorHandler(e, COMPONENT, { setErrors })
    } finally {
        checking.value = false
    }
}

async function runStep(stepName) {
    const payload = {
        step:            stepName,
        order_id:        props.orderId,
        host:            form.host,
        port:            form.port,
        username:        form.username,
        auth_method:     form.auth_method,
        password:        form.auth_method === 'password'    ? form.password    : undefined,
        private_key:     form.auth_method === 'private_key' ? form.private_key : undefined,
        deploy_mode:     form.deploy_mode,
        deploy_path:     form.deploy_path    || undefined,
        sudo_password:   form.sudo_password  || undefined,
        version_id:      form.version_id     || undefined,
        web_user:        form.web_user        || undefined,
        install_domain:  form.install_domain  || undefined,
        install_email:   form.install_email   || undefined,
        web_server:      form.web_server      || undefined,
        ssl_type:        form.ssl_type        || undefined,
        ssl_cert_path:   form.ssl_cert_path   || undefined,
        ssl_key_path:    form.ssl_key_path    || undefined,
    }
    status.value[stepName] = 'running'
    const res = await http.post(`/deploy-product-step`, payload)
    status.value[stepName] = 'ok'
    return res.data?.data ?? {}
}

async function startDeploy() {
    if (! await validateForm(deploySchema, form, setErrors)) return

    deploying.value    = true
    errorMessage.value = ''
    status.value       = {}
    step.value         = 3

    try {
        await runStep('verify')

        if (form.deploy_mode === 'fresh_install') {
            result.value = { ...await runStep('install') }
        } else {
            await runStep('upload')
            result.value = { ...await runStep('extract') }
        }

        step.value = 4
    } catch (err) {
        errorMessage.value = err?.response?.data?.message ?? err?.message ?? __('message.deploy_failed_generic')
        const running = Object.keys(status.value).find(k => status.value[k] === 'running')
        if (running) status.value[running] = 'error'
    } finally {
        deploying.value = false
    }
}

function closeModal() {
    if (deploying.value || checking.value) return
    if (step.value === 4) return resetWizard()
    showModal.value = false
    step.value      = 0
}

function resetWizard() {
    showModal.value    = false
    step.value         = 0
    status.value       = {}
    errorMessage.value = ''
    result.value       = {}
    form.deploy_mode   = ''
}
</script>

<style scoped>
/* Tab view: footer row under the cards */
.dw-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}
.dw-guide { font-weight: 500; }
.dw-btn { font-size: .85rem; padding: .7rem 2rem; }

/* Step line under the popup title */
.dw-subtitle { font-size: .85rem; color: #6c757d; margin-top: .125rem; }

/* Password / Private key toggle in the theme colour */
.dw-toggle .btn { min-width: 8rem; padding: .55rem 1.25rem; font-size: .9rem; }
.dw-toggle .btn-outline-primary { color: #212529; border-color: #dee2e6; background: #fff; }
.dw-toggle .btn-check:checked + .btn-outline-primary {
    color: #fff;
    background-color: var(--primary, #0088cc);
    border-color: var(--primary, #0088cc);
}

.dw-hint { margin-top: -.75rem; margin-bottom: 1rem; }
.dw-note { color: #6c757d; }
</style>
