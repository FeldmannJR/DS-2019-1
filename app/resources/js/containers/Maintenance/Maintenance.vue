<template>
  <div class="maintenance">
    <v-dialog v-model="deleteDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Excluir Usuário</span>
        </v-card-title>

        <v-card-text>
          <v-container class="text-xs-center" grid-list-md>
            Digite o nome do usuário para confirmar
            <v-text-field class="centered-input" v-model="deletedUser" />
            <h3 class="red--text">{{deletingIndex != -1 ? items[deletingIndex].name : null }}</h3>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="closeDelete">Cancelar</v-btn>
          <v-btn flat @click="confirmUserDelete" :disabled="!confirmDelete">Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="editDialog" max-width="500px">
      <template v-slot:activator="{ on }">
        <v-btn id="addUser" flat class="mb-2" v-on="on"><v-icon>add</v-icon>Adicionar usuário</v-btn>
      </template>
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-form :lazy-validation="true" ref="form">
              <v-layout wrap>
                <v-flex xs12>
                  <v-text-field v-model="editedUser.name" label="Nome" :rules="nameRules"></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-select
                    v-model="editedUser.type"
                    label="Tipo"
                    :items="Object.keys(userTypes).map((type) => {
                      return { text: userType(type), value: type }
                    })"></v-select>
                </v-flex>
                <v-flex xs12>
                  <v-text-field
                    v-model="editedUser.pass"
                    :append-icon="showPass ? 'visibility' : 'visibility_off'"
                    :type="showPass ? 'text' : 'password'"
                    name="input-10-2"
                    label="Senha"
                    class="input-group--focused"
                    :rules="[v => !!v || 'Insira uma senha']"
                    required
                    @click:append="showPass = !showPass"
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click="closeEdit">Cancelar</v-btn>
          <v-btn flat @click="save">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-data-table
      :headers="headers"
      :items="items"
      :rows-per-page-text="'Usuários por página'"
      :rows-per-page-items="[10,15,30]"
      class="usersTable elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.name }}</td>
        <td>{{ userType(props.item.type) }}</td>
        <td>{{ props.item.pass }}</td>
        <td>
          <v-icon
            @click="editUser(props.item)"
          >
            edit
          </v-icon>
          <v-icon
            @click="deleteUser(props.item)"
          >
            delete
          </v-icon>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
require("./Maintenance.scss");

export default {
  props: ["users"],
  data() {
    return {
      editDialog: false,
      deleteDialog: false,
      items: [],
      root: null,
      headers: [
        { text: "ID", value: "id" },
        { text: "Usuário", value: "name" },
        { text: "Tipo", value: "type"},
        { text: "Senha", value: "pass"},
        { text: 'Opções', value: 'name', sortable: false }
      ],
      userTypes: {
        "A": "Administrador",
        "S": "Estatística",
        "R": "Funcionário"
      },
      validUser: false,
      currentUser: '',
      showPass: false,
      editedIndex: -1,
      editedUser: {
        name: '',
        type: 'R',
        pass: '',
      },
      defaultUser: {
        name: '',
        type: 'R',
        pass: '',
      },
      deletingIndex: -1,
      deletedUser: '',
    }
  },
  computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Novo Usuário' : 'Editar Usuário'
      },
      nameRules() {
        return [
          v => !!v || 'Insira um nome de usuário',
          v => {
            if(v == this.currentUser) return true;

            var existingUser = this.items.filter(item => {
              return item.name == v
            })

            return existingUser.length == 0 || 'Usuário com este nome já existe'
          }
        ]
      },
      confirmDelete() {
        return this.deletingIndex != -1 && this.deletedUser == this.items[this.deletingIndex].name
      }
    },
  watch: {
    editDialog (val) {
      val || this.closeEdit()
    },
    deleteDialog (val) {
      val || this.closeDelete()
    },
  },
  created() {
    this.initialize()
  },
  methods: {
    initialize() {
      this.items = this.users
    },
    userType (type) {
      return this.userTypes[type]
    },
    deleteUser (user) {
      this.deletingIndex = this.users.indexOf(user)
      this.deleteDialog = true
    },
    editUser (user) {
      this.editedIndex = this.users.indexOf(user)
      this.editedUser = Object.assign({}, user)
      this.currentUser = user.name
      this.editDialog = true
    },
    closeEdit () {
      this.editDialog = false
      setTimeout(() => {
        this.editedUser = Object.assign({}, this.defaultUser)
        this.currentUser = ''
        this.editedIndex = -1
        this.validUser = false
        this.$refs.form.resetValidation()
      }, 300)
    },
    save () {
      if(this.validate()) {
        if (this.editedIndex > -1) {
          Object.assign(this.items[this.editedIndex], this.editedUser)
        } else {
          this.items.push(this.editedUser)
        }
        this.closeEdit()
      }
    },
    closeDelete () {
      this.deleteDialog = false
      setTimeout(() => {
        this.deletingIndex = -1
        this.deletedUser = ''
      }, 300)
    },
    confirmUserDelete() {
      this.items.splice(this.deletingIndex, 1)
      this.closeDelete()
    },
    validate () {
      if (this.$refs.form.validate()) {
        this.validUser = true
        this.snackbar = true
      }

      return this.validUser
    },
  }
};
</script>