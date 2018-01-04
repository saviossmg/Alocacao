<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Vwcurso
 *
 * @ORM\Table(name="vwcurso", indexes={@ORM\Index(name="fk_vwCurso_unidade1_idx", columns={"Unidade"})})
 * @ORM\Entity
 */
class Vwcurso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nome", type="string", length=45, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="Sigla", type="string", length=45, nullable=false)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="CodCurso", type="string", length=45, nullable=false)
     */
    private $codcurso;

    /**
     * @var \Unidade
     *
     * @ORM\ManyToOne(targetEntity="Unidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Unidade", referencedColumnName="Id")
     * })
     */
    private $unidade;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Vwcurso
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set sigla
     *
     * @param string $sigla
     *
     * @return Vwcurso
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set codcurso
     *
     * @param string $codcurso
     *
     * @return Vwcurso
     */
    public function setCodcurso($codcurso)
    {
        $this->codcurso = $codcurso;

        return $this;
    }

    /**
     * Get codcurso
     *
     * @return string
     */
    public function getCodcurso()
    {
        return $this->codcurso;
    }

    /**
     * Set unidade
     *
     * @param \Unidade $unidade
     *
     * @return Vwcurso
     */
    public function setUnidade(\Unidade $unidade = null)
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * Get unidade
     *
     * @return \Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }
}

