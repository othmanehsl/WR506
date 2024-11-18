<?php 
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

/**
 * @implements ProcessorInterface<User, Operation>
 */
final class UserPasswordHasher implements ProcessorInterface
{
    /**
     * @var ProcessorInterface<User, Operation>
     */
    private ProcessorInterface $processor;

    /**
     * @param ProcessorInterface<User, Operation> $processor
     */
    public function __construct(
        ProcessorInterface $processor,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        $this->processor = $processor;
    }

    /**
     * @param User $data
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data->getPlainPassword()) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
