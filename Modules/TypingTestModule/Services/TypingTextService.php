<?php

namespace Modules\TypingTestModule\Services;

use Modules\TypingTestModule\Repository\TypingTextRepository;

class TypingTextService {
    private $textRepository;

    public function __construct(TypingTextRepository $textRepository) {
        $this->textRepository = $textRepository;
    }

    public function findAll() {
        return $this->textRepository->latestFirst();
    }

    public function find($id) {
        return $this->textRepository->find($id);
    }

    public function create(array $data) {
        return $this->textRepository->create($this->payload($data));
    }

    public function update(array $data) {
        return $this->textRepository->update($this->payload($data), $data['id']);
    }

    public function delete($id) {
        return $this->textRepository->delete($id);
    }

    public function randomActive(int $minWords) {
        return $this->textRepository->randomActive($minWords);
    }

    private function payload(array $data): array {
        $content = trim(preg_replace('/[ \t]+/u', ' ', $data['content']));

        return [
            'title'       => $data['title'] ?? null,
            'content'     => $content,
            'words_count' => count(TypingEvaluator::words($content)),
            'is_active'   => !empty($data['is_active']),
        ];
    }
}
