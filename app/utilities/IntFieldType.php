<?php 

  enum IntFieldType: string {

    case DNI = "dni";
    case PHONE = "telefono";

    public static function intToValidate( int $value, IntFieldType $intFT ) {

      $value = trim( $value );

      if( empty( $value ) ) {
        return false;
      }

      switch( $intFT ) {

        case self::DNI:
          return self::validateDNI( $value );
        case self::PHONE:
          return self::validatePhone( $value );
        default:
          return false;

      }

    }

    private static function validateDNI( int $value ): bool {
      //supongo que va a entrar ya como un int, asi que valida nomas que este en este rango
      return $value >= 1000000 && $value <= 99999999;

    }

    private static function validatePhone( string $value ): bool {
      //si entra como string, para que puedan poner -
      $length = strlen($value);
     //elimina espacios, guiones y parentesis
      $value = preg_replace('/[\s\-\(\)]/', '', $value);

      //permite el prefijo de argentina +54
      $value = preg_replace('/^\+54/', '', $value);

      //permite solo digitos
      if (!ctype_digit($value)) {
        return false;
      }
      //longitud entre 8 y 15
      if ($length < 8 || $length > 15) {
        return false;
      }

      return true;

      //si entra como un int usar esto=
      // $length = strlen((string)$value);
      
      // return $length >= 8 && $length <= 15;

    }

  }

?>