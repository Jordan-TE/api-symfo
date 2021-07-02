<?php

namespace App\Controller;


use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractFOSRestController
{

    /**
     * @Route("/book", name="books", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function indexAction(NormalizerInterface $normalizer, Request $request): JsonResponse
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

        if (!$books) {
            throw new NotFoundHttpException('No book found');
        }
        $normalizedBooks = (array)$normalizer->normalize($books);
        if ($sort = (string)$request->query->get('sorting')) {
            $normalizedBooks = $this->sortBooks($normalizedBooks, $sort);
        }

        return new JsonResponse($normalizedBooks, Response::HTTP_OK);
    }

    /**
     * Should be  done in repo with sql but for the testing example i made a simple sorting function here
     * @param array[] $books
     * @param string|null $sorting
     * @return array[]
     */
    public function sortBooks(array $books, string|null $sorting = 'asc'): array
    {
        if ($sorting === 'desc') {
            usort(
                $books,
                function (array $a, array $b) {
                    return -($a['pages'] <=> $b['pages']);
                }
            );
        } elseif ($sorting === 'asc') {
            usort(
                $books,
                function (array $a, array $b) {
                    return ($a['pages'] <=> $b['pages']);
                }
            );
        }
        return $books;
    }

    /**
     * @Route("/book/{id}", name="book", methods={"GET"})
     * @throws ExceptionInterface
     */
    public function getAction(Request $request, NormalizerInterface $normalizer): JsonResponse
    {
        $bookId = $request->get('id');

        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $bookId]);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }
        $normalizedBook = $normalizer->normalize($book);

        return new JsonResponse($normalizedBook, Response::HTTP_OK);
    }

    /**
     * @Route("/book", name="set-book", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function PostAction(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $book = $serializer->deserialize($request->getContent(), Book::class, 'json');
        if (!$book) {
            throw new NotFoundHttpException('No book found');
        }
        $em->persist($book);
        $em->flush();

        return new JsonResponse($normalizer->normalize($book), Response::HTTP_CREATED);
    }

    /**
     * @Route("/book/{id}", name="update-book", methods={"PUT"})
     *
     * @throws ExceptionInterface
     */
    public function putBookAction(
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $bookNewValues = json_decode((string)$request->getContent(), true);
        $bookId = $request->get('id');
        /**
         * @var Book
         */
        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $bookId]);
        $book->setTitle($bookNewValues['title']);
        $book->setAuthor($bookNewValues['author']);
        $book->setSummary($bookNewValues['summary']);
        $book->setPages($bookNewValues['pages']);


        $em->persist($book);
        $em->flush();

        return new JsonResponse($normalizer->normalize($book), Response::HTTP_OK);
    }

    /**
     * @Route("/book/{id}", name="delete-book", methods={"DELETE"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function deleteBookAction(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $bookId = $request->get('id');

        $book = $this->getDoctrine()->getRepository(Book::class)->findOneBy(['id' => $bookId]);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }
        $em->remove($book);
        $em->flush();

        return new JsonResponse("Book Successfully removed", Response::HTTP_OK);
    }
}