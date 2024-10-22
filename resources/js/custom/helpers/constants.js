App.Constants = {
  ENV: null,
  IMAGE_PLACEHOLDER: null,
  BASE_URL: null,
  API_HOST: null,
  CSRF_TOKEN: null,
  ON: 1,
  OFF: 0,
  DEFAULT_APP_COLOR: 'rgb(19, 123, 222)',
  default_country_code: 92,
  default_country_id: 178,
  default_lat: 24.8259898,
  default_long: 66.9890924,
  maximumAttributeSection: 3,
  agencyTypeId: 3,
  itemSearchLength: 4,
  phoneNumberLength: 7,
  defaultCountryNameCode:"pk",
  googleAutoComplete:'',
  googleAutoCompleteLsr:'',
  googleAutoCompleteOption:null,
  userType:null,
  IMGIX_BASE_PATH:null,
  endPoints: {
      'deleteUserAccount': '/users-delete-account',
      'getCountriesData': '/get-active-countries',
      'usersList': '/users-list',
      'createUser': '/user-save',
      'changeUserStatus': '/user-change-status',
      'editUser': "/users/edit",
      'bulkDeleteUsers': "/users-delete/selected",
      'getPosts': '/post-list',
      'createPost': '/post-save',
      'editPost': '/post-edit',
  },
    user_type:{
      1 : 'Admin'
    },
    http_statues: {
        'success' : 200,
        'failed' : 400,
        'validationError' : 422,
        'authenticationError' : 401,
        'authorizationError' : 403,
        'serverError': 500,
    },
}
