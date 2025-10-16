<?php 

  enum StringFieldType: string {

    case NAME = "nombre";
    case SURNAME = "apellido";
    case EMAIL = "correo electronico";
    case ADDRESS = "direccion";
    case USER_NAME = "nombre de usuario";
    case PASSWORD = "contraseña";
    case ROL = "rol";

    //value es el valor que entra (para validar) y stringFT es un caso de arriba
    public static function stringToValidate( string $value, StringFieldType $stringFT ) {
      //se recorta el valor a validar
      $value = trim( $value );
      //si esta vacio termina to
      if( empty( $value ) ) {
        return false;
      }

      //segun el stringft que pasamos al llamar el metodo retornara el correcto, algo asi, si no retornara falso
      switch( $stringFT ) {
        case self::NAME:
          return self::validateName( $value );
        case self::SURNAME:
          return self::validateSurname( $value );
        case self::EMAIL:
          return self::validateEmail( $value );
        case self::ADDRESS:
          return self::validateAddress( $value );
        case self::USER_NAME:
          return self::validateUserName( $value );
        case self::PASSWORD:
          return self::validatePassword( $value );
        case self::ROL:
          return self::validateRol( $value );
        default:
          return false;
      }

    }

    private static function validateName( string $value ): bool {
      //toma la longitud del valor
      $length = strlen( $value );
      //valida el largo
      if( $length < 3 || $length > 100 ) {
        return false;
      }
      //valida q solo tenga letras, espacios, guiones y apostrofes '
      if( !preg_match( "/^[a-záéíóúñü\s'\-]+$/iu", $value ) ) {
        return false;
      }
      //no permite multiples espacios seguidos
      if( preg_match("/\s{2,}/", $value ) ) {
        return false;
      }
      //no permite guiones o apostrofes seguidos
      if ( preg_match("/(--|'')/", $value ) ) {
        return false;
      }
      //no permite que empiece o temrine con guion o apostrofe
      if (preg_match("/^[-']|[-']$/", $value)) {
        return false;
      }
      //debe contener al menos una letra
      if (!preg_match("/[a-záéíóúñü]/iu", $value)) {
        return false;
      }
      //si el valor pasa todo, da como correcta la validacion
      return true;

    }

    private static function validateSurname( string $value ): bool {
      //lo mismo q nombre
      $length = strlen( $value );

      if ( $length < 2 || $length > 50 ) {
        return false;
      }

      if ( !preg_match("/^[a-záéíóúñü\s'\-]+$/iu", $value ) ) {
        return false;
      }

      if ( preg_match( "/\s{2,}/", $value ) ) {
        return false;
      }

      if (preg_match("/(--|'')/", $value)) {
        return false;
      }

      if (preg_match("/^[-']|[-']$/", $value)) {
        return false;
      }

      if (!preg_match("/[a-záéíóúñü]/iu", $value)) {
        return false;
      }

      return true;
    }

    private static function validateEmail( string $value ): bool {
      //valida que el email sea correcto
      if ( !filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
        return false;
      }

      return true;
    }

    private static function validateAddress( string $value ): bool {
      $length = strlen($value);

      if ($length < 5 || $length > 150) {
        return false;
      }

      if (!preg_match("/^[a-z0-9áéíóúñü\s\.,'\-#]+$/iu", $value)) {
        return false;
      }

      if (preg_match("/\s{2,}/", $value)) {
        return false;
      }

      return true;
    }

    private static function validateUserName( string $value ):bool {

      $length = strlen($value);

      if ($length < 3 || $length > 20) {
        return false;
      }

      //tiene q empezar con una letra 
      if (!preg_match("/^[a-z]/i", $value)) {
        return false;
      }

      //solo letras, numeros, guiones y guion bajo
      if (!preg_match("/^[a-z0-9_-]+$/i", $value)) {
        return false;
      }

      //no guiones o guiones bajos consecutivos
      if (preg_match("/(--|__)/", $value)) {
        return false;
      }

      return true;

    }

    private static function validatePassword( string $value ): bool {

      $length = strlen($value);

      if ($length < 8 || $length > 50) {
        return false;
      }

      //q tenga al menos una letra
      if (!preg_match("/[a-z]/i", $value)) {
        return false;
      }

      //al menos un numero
      if (!preg_match("/[0-9]/", $value)) {
        return false;
      }

      //solo letras, numeros y caracteres simples
      if (!preg_match("/^[a-z0-9!@#$%&*?]+$/i", $value)) {
        return false;
      }

      return true;

    }

    private static function validateRol( string $value ):bool {
      //el valor ingresado lo pone en minusculas
      $value = strtolower( $value );
      //compara el valor con las opciones del array, si coincide es verdadero, si no, false
      return in_array( $value, ["coordinador", "docente"], true );

    }

  }

?>