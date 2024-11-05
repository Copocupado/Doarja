class ValidationRules {
  private password: string

  constructor (password: string) {
    this.password = password
  }

  private required = (message: string) => (value: any) => !!value || message

  private minLength = (min: number, message: string) => (value: string) => {
    return value.length >= min || message
  }

  private match = (otherValue: () => string, message: string) => (value: string) => {
    return value === otherValue() || message
  }

  private validPhoneNumber = (message: string) => (value: string) => {
    const phonePattern = /^\d{11}$/
    return phonePattern.test(value) || message
  }

  private validEmail = (message: string) => (value: string) => {
    if (value === 'root') return true
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailPattern.test(value) || message
  }

  private validFile = (maxSize: number, message: string) => (value: File | null) => {
    const file = value[0]

    if (file === undefined) return message

    if (file.size > maxSize) {
      return `O tamanho do arquivo deve ser menor que ${maxSize / 1024 / 1024} MB.`
    }

    return true
  }

  private numericOnly = (message: string) => (value: string) => {
    const numericPattern = /^[0-9]*$/
    return numericPattern.test(value) || message
  }

  private validDescription = (min: number, max: number, message: string) => (value: string) => {
    return (value.length >= min && value.length <= max) || message
  }

  get nameRules () {
    return [
      this.required('O nome é obrigatório.'),
      this.minLength(3, 'O nome deve ter pelo menos 3 caracteres.'),
    ]
  }

  get passwordRules () {
    return [
      this.required('Senha é obrigatória.'),
    ]
  }

  get confirmPasswordRules () {
    return [
      this.required('Senha é obrigatória.'),
      this.match(() => this.password, 'Senhas não coincidem'),
    ]
  }

  get phoneRules () {
    return [
      this.required('O número de telefone é obrigatório.'),
      this.validPhoneNumber('O número de telefone deve ter 11 dígitos.'),
    ]
  }

  get emailRules () {
    return [
      this.required('O email é obrigatório.'),
      this.validEmail('O email deve ser válido ou "root".'),
    ]
  }

  get fileRules () {
    const maxSize = 2 * 1024 * 1024
    return [
      this.validFile(maxSize, 'Um arquivo deve ser selecionado.'),
    ]
  }

  get descriptionRules () {
    return [
      this.required('A descrição é obrigatória.'),
      this.validDescription(10, 500, 'A descrição deve ter entre 10 e 500 caracteres.'),
    ]
  }

  get numericRules () {
    return [
      this.required('Este campo é obrigatório.'),
      this.numericOnly('Este campo deve conter apenas números.'),
    ]
  }
}

export { ValidationRules }
