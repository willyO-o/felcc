<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registro_criminal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_criminal');
    }
};

/*

CREATE TABLE personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(200) NOT NULL,
    nombre_supuesto VARCHAR(200),
    nacionalidad VARCHAR(100),
    lugar_nacimiento VARCHAR(150),
    fecha_nacimiento DATE,
    edad_aproximada INT,
    sexo ENUM('MASCULINO','FEMENINO','OTRO'),
    estado_civil ENUM('SOLTERO','CASADO','DIVORCIADO','VIUDO','CONCUBINO'),
    nombre_conyuge VARCHAR(200),
    domicilio VARCHAR(250),
    ocupacion VARCHAR(150),
    division VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    tipo_documento ENUM('CI','PASAPORTE','LICENCIA','OTRO'),
    numero_documento VARCHAR(50),
    complemento VARCHAR(10),
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);


CREATE TABLE alias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    alias VARCHAR(100) NOT NULL,
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);

CREATE TABLE registros_criminales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_registro DATE NOT NULL,
    especialidad VARCHAR(150),
    rasgos TEXT,
    reincidente BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (persona_id) REFERENCES personas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE modus_operandi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

CREATE TABLE zonas_operacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    zona VARCHAR(150),
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

CREATE TABLE observaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    observacion TEXT,
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

CREATE TABLE fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    tipo ENUM('FRONTAL','LATERAL'),
    ruta_foto VARCHAR(255),
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);



///corregido

CREATE TABLE alias_registro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    alias VARCHAR(100) NOT NULL,
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

CREATE TABLE fotos_registro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    tipo ENUM('FRONTAL','LATERAL','EVIDENCIA'),
    ruta_foto VARCHAR(255),
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);
CREATE TABLE observaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    observacion TEXT,
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

CREATE TABLE modus_operandi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (registro_id) REFERENCES registros_criminales(id)
);

*/
