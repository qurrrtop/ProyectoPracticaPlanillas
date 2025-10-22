<?php

  declare( strict_types = 1 );

  namespace app\utilities;

  use DateTime;
  use InvalidArgumentException;


  class DateTimeValidator {
    
    public static function ValidateFNacimiento( string $value ): string {

      $value = trim( $value );

      if ( $value === '' ) {
        throw new InvalidArgumentException("Fecha de nacimiento no puede estar vacía.");
      }

      $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'];

      $date = null;
      foreach ($formats as $format) {
        $d = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($d && $errors['warning_count'] === 0 && $errors['error_count'] === 0 && $d->format($format) === $value) {
          $date = $d;
          break;
        }
      }

      if (!$date) {
        // Intentar un parseo más flexible como último recurso
        try {
          $d = new DateTime($value);
          $date = $d;
        } catch (\Exception $e) {
          throw new InvalidArgumentException("Fecha de nacimiento ingresada con formato inválido (aceptados DD-MM-YYYY, DD/MM/YYYY, YYYY-MM-DD).");
        }
      }

      $today = new DateTime();
      if ( $date > $today ) {
        throw new InvalidArgumentException( "La fecha de nacimiento no puede ser futura." );
      }

      return $date->format( 'Y-m-d' );

    }

  }

?>