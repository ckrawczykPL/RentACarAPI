<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Vehicle;
use App\Form\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api")
 */
class DefaultController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Creates a new vehicle.
     *
     * @Route("/vehicle/create", name="create", methods={"POST"})
     * @OA\Post(
     *     path="/api/vehicle/create",
     *     summary="Creates a new vehicle",
     *     description="This endpoint allows the creation of a new vehicle.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brand", type="string", example="Toyota"),
     *             @OA\Property(property="registrationNumber", type="string", example="ABC123"),
     *             @OA\Property(property="vin", type="string", example="1HGBH41JXMN109186"),
     *             @OA\Property(property="clientEmail", type="string", example="client@example.com"),
     *             @OA\Property(property="clientAddress", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="isCurrentlyRented", type="boolean", example=true),
     *             @OA\Property(property="currentLocationAddress", type="string", example="456 Rental St, City, Country")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Vehicle created!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data"
     *     )
     * )
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $brand = $vehicle->getBrand();

            if (!$brand) {
                return new JsonResponse(['status' => 'Brand not found'], Response::HTTP_BAD_REQUEST);
            }

            $vehicle->setBrand($brand);

            $entityManager->persist($vehicle);
            $entityManager->flush();

            return new JsonResponse(['status' => 'Vehicle created!'], Response::HTTP_CREATED);
        }

        return new JsonResponse([
            'status' => 'Invalid form data',
            'errors' => $form->getErrors(true, false)
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Reads the details of a vehicle.
     *
     * @Route("/vehicle/read/{id}", name="read", methods={"GET"})
     * @OA\Get(
     *     path="/api/vehicle/read/{id}",
     *     summary="Reads the details of a vehicle",
     *     description="This endpoint retrieves the details of a specific vehicle by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vehicle ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="brand", type="string", example="Toyota"),
     *             @OA\Property(property="registrationNumber", type="string", example="ABC123"),
     *             @OA\Property(property="vin", type="string", example="1HGBH41JXMN109186"),
     *             @OA\Property(property="clientEmail", type="string", example="client@example.com"),
     *             @OA\Property(property="clientAddress", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="isCurrentlyRented", type="boolean", example=true),
     *             @OA\Property(property="currentLocationAddress", type="string", example="456 Rental St, City, Country")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     )
     * )
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function read(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $vehicle = $entityManager->getRepository(Vehicle::class)->findOneBy(['id' => $id]);

        if (!$vehicle) {
            return new JsonResponse(['status' => 'Vehicle not found'], Response::HTTP_NOT_FOUND);
        }

        $vehicleData = $this->serializer->normalize($vehicle);

        return new JsonResponse($vehicleData);
    }

    /**
     * Updates the details of a vehicle.
     *
     * @Route("/vehicle/update/{id}", name="update", methods={"PUT"})
     * @OA\Put(
     *     path="/api/vehicle/update/{id}",
     *     summary="Updates the details of a vehicle",
     *     description="This endpoint allows updating the details of an existing vehicle.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vehicle ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brand", type="string", example="Honda"),
     *             @OA\Property(property="registrationNumber", type="string", example="XYZ789"),
     *             @OA\Property(property="vin", type="string", example="2HGBH41JXMN109187"),
     *             @OA\Property(property="clientEmail", type="string", example="newclient@example.com"),
     *             @OA\Property(property="clientAddress", type="string", example="456 New St, City, Country"),
     *             @OA\Property(property="isCurrentlyRented", type="boolean", example=false),
     *             @OA\Property(property="currentLocationAddress", type="string", example="789 New Rental St, City, Country")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Vehicle updated!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     )
     * )
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $vehicle = $entityManager->getRepository(Vehicle::class)->find($id);

        if (!$vehicle) {
            return new JsonResponse(['status' => 'Vehicle not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse([
            'status' => 'Invalid form data',
            'errors' => $form->getErrors(true, false)
            ], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Vehicle updated!'], Response::HTTP_OK);
    }

    /**
     * Deletes a vehicle.
     *
     * @Route("/vehicle/delete/{id}", name="delete", methods={"DELETE"})
     * @OA\Delete(
     *     path="/api/vehicle/delete/{id}",
     *     summary="Deletes a vehicle",
     *     description="This endpoint deletes a specific vehicle by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vehicle ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Vehicle deleted!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found"
     *     )
     * )
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $vehicle = $entityManager->getRepository(Vehicle::class)->find($id);

        if (!$vehicle) {
            return new JsonResponse(['status' => 'Vehicle not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($vehicle);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Vehicle deleted!']);
    }

    /**
     * Returns a list of all vehicles.
     *
     * @Route("/vehicle/list", methods={"GET"})
     * @OA\Get(
     *     path="/api/vehicle/list",
     *     summary="Returns a list of all vehicles",
     *     description="This endpoint returns a list of all vehicles.",
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="brand", type="string", example="Toyota"),
     *                 @OA\Property(property="registrationNumber", type="string", example="ABC123"),
     *                 @OA\Property(property="vin", type="string", example="1HGBH41JXMN109186"),
     *                 @OA\Property(property="clientEmail", type="string", example="client@example.com"),
     *                 @OA\Property(property="clientAddress", type="string", example="123 Main St, City, Country"),
     *                 @OA\Property(property="isCurrentlyRented", type="boolean", example=true),
     *                 @OA\Property(property="currentLocationAddress", type="string", example="456 Rental St, City, Country")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No vehicles found"
     *     )
     * )
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function fullList(EntityManagerInterface $entityManager): JsonResponse
    {
        $vehicles = $entityManager->getRepository(Vehicle::class)->findAll();
        $data = $this->serializer->normalize($vehicles);
        return new JsonResponse($data);
    }

    /**
     * Returns a list of all brands.
     *
     * @Route("/brand/list", methods={"GET"})
     * @OA\Get(
     *     path="/api/brand/list",
     *     summary="Returns a list of all brands",
     *     description="This endpoint returns a list of all vehicle brands.",
     *     @OA\Response(
     *         response=200,
     *         description="List of brands",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Toyota")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No brands found"
     *     )
     * )
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function brandList(EntityManagerInterface $entityManager): JsonResponse
    {
        $brands = $entityManager->getRepository(Brand::class)->findBy([], ['id' => 'ASC']);

        if (!$brands) {
            return new JsonResponse(['status' => 'No brands found'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->normalize($brands);

        return new JsonResponse($data);
    }

}
