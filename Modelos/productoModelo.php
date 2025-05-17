<?php

	function obtenerTotalProductos($conexion) {
		$stmt = $conexion->query("SELECT COUNT(*) as total FROM Productos");
		$total = $stmt->fetch(PDO::FETCH_ASSOC);
		return $total['total'];
	}



	function obtenerProductosPaginados($conexion, $limite, $offset) {
		$sql = "
			SELECT 
				p.idProducto,
				p.nombreProducto,
				p.descripcion,
				p.precio,
				p.imagen,
				c.nombreCategoria,
				ROUND(AVG(cal.calificacion), 1) AS promedio
			FROM Productos p
			LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
			LEFT JOIN Calificaciones cal ON p.idProducto = cal.idProducto
			GROUP BY p.idProducto
			LIMIT :limite OFFSET :offset
		";

		$stmt = $conexion->prepare($sql);
		$stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Archivo: Modelos/ProductoModelo.php

	function obtenerProductoPorId($conexion, $idProducto) {
		$query = "
			SELECT 
				p.idProducto,
				p.nombreProducto,
				p.descripcion,
				p.precio,
				p.imagen,
				c.nombreCategoria,
				p.stock,
				ROUND(AVG(cal.calificacion), 1) AS promedio
			FROM Productos p
			LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria
			LEFT JOIN Calificaciones cal ON p.idProducto = cal.idProducto
			WHERE p.idProducto = :idProducto
			GROUP BY p.idProducto
		";

		$stmt = $conexion->prepare($query);
		$stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	function obtenerProductosConCategorias($conexion) {
		$sql = "SELECT p.*, c.nombreCategoria 
				FROM Productos p 
				LEFT JOIN Categorias c ON p.idCategoria = c.idCategoria";
		$stmt = $conexion->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function obtenerProductosConFiltro($conexion, $buscar = '', $categoria = '', $orden = '') {
		$sql = "SELECT p.*, c.nombreCategoria 
				FROM Productos p 
				JOIN Categorias c ON p.idCategoria = c.idCategoria 
				WHERE 1=1";

		$params = [];

		if (!empty($buscar)) {
			$sql .= " AND p.nombreProducto LIKE :buscar";
			$params[':buscar'] = '%' . $buscar . '%';
		}

		if (!empty($categoria) && $categoria !== 'Todas las categorías') {
			$sql .= " AND c.nombreCategoria = :categoria";
			$params[':categoria'] = $categoria;
		}

		switch ($orden) {
			case 'nombre_asc':
				$sql .= " ORDER BY p.nombreProducto ASC";
				break;
			case 'nombre_desc':
				$sql .= " ORDER BY p.nombreProducto DESC";
				break;
			case 'stock_desc':
				$sql .= " ORDER BY p.stock DESC";
				break;
			default:
				$sql .= " ORDER BY p.idProducto ASC"; // Orden por defecto
				break;
		}

		$stmt = $conexion->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function editarProducto($conexion, $datos, $imagenNueva = null) {
		$sql = "UPDATE Productos SET 
					nombreProducto = :nombre,
					descripcion = :descripcion,
					precio = :precio,
					stock = :stock,
					idCategoria = :idCategoria";

		// Si hay imagen nueva, agrega al query
		if ($imagenNueva !== null) {
			$sql .= ", imagen = :imagen";
		}

		$sql .= " WHERE idProducto = :id";

		$stmt = $conexion->prepare($sql);

		// Asignar parámetros comunes
		$stmt->bindParam(':nombre', $datos['nombreProducto']);
		$stmt->bindParam(':descripcion', $datos['descripcion']);
		$stmt->bindParam(':precio', $datos['precio']);
		$stmt->bindParam(':stock', $datos['stock']);
		$stmt->bindParam(':idCategoria', $datos['categoria']);
		$stmt->bindParam(':id', $datos['idProducto']);

		// Si hay imagen nueva, la asignamos
		if ($imagenNueva !== null) {
			$stmt->bindParam(':imagen', $imagenNueva, PDO::PARAM_LOB);
		}

		return $stmt->execute();
	}

	function agregarProducto($conexion, $datos, $imagen) {
		$sql = "INSERT INTO Productos (nombreProducto, descripcion, precio, stock, idCategoria, imagen)
				VALUES (:nombre, :descripcion, :precio, :stock, :idCategoria, :imagen)";
		$stmt = $conexion->prepare($sql);
		$stmt->bindParam(':nombre', $datos['nombreProducto']);
		$stmt->bindParam(':descripcion', $datos['descripcion']);
		$stmt->bindParam(':precio', $datos['precio']);
		$stmt->bindParam(':stock', $datos['stock']);
		$stmt->bindParam(':idCategoria', $datos['categoria']);
		$stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
		$stmt->execute();
	}
?>
