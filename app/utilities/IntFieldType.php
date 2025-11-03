<?php 

  declare( strict_types = 1 );

  namespace app\utilities;

  enum IntFieldType: string {

    case DNI = "dni";
    case PHONE = "telefono";

    public static function intToValidate( int $value, IntFieldType $intFT ) {

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
      
      return $value >= 1000000 && $value <= 99999999;

    }

    private static function validatePhone( int $value ): bool {

      $length = strlen((string)$value);
      
      return $length >= 8 && $length <= 15;

    }

  }

?>