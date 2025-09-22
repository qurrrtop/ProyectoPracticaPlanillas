<?php

    class Validation {
        // -------------------- VALIDAR ID --------------------

        public static function validId(int $id): bool {
            return $id > 0;
        }

        // -------------------- VALIDAR STRING --------------------

        public static function noEmpty(string $value): bool {
            return trim($value) !== '';
        }

        // -------------------- VALIDAR EMAIL --------------------

        public static function validEmail(string $email): bool {
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        }

        // -------------------- VALIDAR DNI --------------------
        
        public static function validDni(string $dni): bool {
            return preg_match('/^\d{8}$/', $dni) === 1;
        }

        // -------------------- VALIDAR F NACIMIENTO --------------------

        public static function validFecha(string $fecha): bool {
            $d = DateTime::createFromFormat('Y-m-d', $fecha);
            return $d && $d->format('Y-m-d') === $fecha && $d <= new DateTime(); // no puede ser futura
        }
    }
?>