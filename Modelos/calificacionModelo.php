<?php
	require_once '../Includes/conexion.php';

	class CalificacionModelo {
		private $conexion;

		public function __construct($conexion) {
			$this->conexion = $conexion;
		}

		public function guardarCalificacion($idProducto, $idVisitante, $calificacion) {
			$sql = "INSERT INTO Calificaciones (idProducto, idVisitante, calificacion)
					VALUES (:idProducto, :idVisitante, :calificacion)
					ON DUPLICATE KEY UPDATE calificacion = :calificacion";

			$stmt = $this->conexion->prepare($sql);
			$stmt->bindParam(':idProducto', $idProducto);
			$stmt->bindParam(':idVisitante', $idVisitante);
			$stmt->bindParam(':calificacion', $calificacion);

			return $stmt->execute();
		}
	}
?>