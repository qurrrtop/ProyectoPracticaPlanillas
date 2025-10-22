<?php 

  declare( strict_types = 1 );

  namespace app\utilities;

  use DateTime;
  use InvalidArgumentException;


  class DateTimeValidator {
    
    public static function ValidateFNacimiento( string $value ) {

      $value = trim( $value );

      $date = DateTime::createFromFormat( "d-m-Y", $value );
        if ( !$date || $date->format( 'd-m-Y' ) !== $value ) {
          throw new InvalidArgumentException( "Fecha de nacimiento ingresada con formato inválido (DD-MM-YYYY)." );
      }

      $today = new DateTime();
        if ( $date > $today ) {
          throw new InvalidArgumentException( "La fecha de nacimiento no puede ser futura." );
      }

      return $date->format( 'Y-m-d' );

    }

  }

?>